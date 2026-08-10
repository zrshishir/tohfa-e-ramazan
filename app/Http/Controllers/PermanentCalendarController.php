<?php

namespace App\Http\Controllers;

use App\Models\MazhabWiseScheduleSetting;
use App\Models\PermanentCalendar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermanentCalendarController extends Controller
{
    /**
     * Map PermanentCalendar JSON column names to MazhabWiseScheduleSetting offset fields.
     * Each entry: 'json_column' => ['start_offset_field', 'end_offset_field']
     * If both start and end use the same offset, repeat the field name.
     */
    private const PRAYER_OFFSET_MAP = [
        'sehri'   => ['sehri_time',  'sehri_time'],
        'fazr'    => ['fazr_time',   'fazr_time'],
        'ishraq'  => ['ishraq_time', 'ishraq_time'],
        'johr'    => ['johr_time',   'johr_time'],
        'asr'     => ['asr_time',    'asr_time'],
        'magrib'  => ['magrib_time', 'magrib_time'],
        'esha'    => ['esha_time',   'esha_time'],
    ];

    /**
     * There is no `iftar` column — the fast is broken when Magrib begins, so iftar is
     * derived from `magrib` with the mazhab's own `iftar_time` offset applied.
     *
     * `ramazanCalendar()` previously named `iftar` in its select() and every request
     * died with "Unknown column 'iftar' in 'field list'".
     */
    private function deriveIftar(?array $rawMagrib, ?MazhabWiseScheduleSetting $mazhabSetting): ?array
    {
        if (empty($rawMagrib) || empty($rawMagrib['start_time'])) {
            return null;
        }

        $offset = (int) ($mazhabSetting->iftar_time ?? 0);

        return [
            'text_en'    => 'Iftar',
            'text_bn'    => 'ইফতার',
            'text_ar'    => 'إفطار',
            'start_time' => $this->adjustTime($rawMagrib['start_time'], $offset),
            'end_time'   => isset($rawMagrib['end_time'])
                ? $this->adjustTime($rawMagrib['end_time'], $offset)
                : null,
        ];
    }

    /**
     * Adjust a time string like "05:22 AM" by +/- minutes.
     * Returns the original string unchanged if it doesn't match the expected format.
     */
    private function adjustTime(string $time, int $offsetMinutes): string
    {
        if ($offsetMinutes === 0) {
            return $time;
        }
        // Only adjust strings that strictly match "hh:mm AM/PM"
        if (!preg_match('/^\d{1,2}:\d{2}\s*(AM|PM)$/i', trim($time))) {
            return $time;
        }
        return Carbon::createFromFormat('h:i A', trim($time))
            ->addMinutes($offsetMinutes)
            ->format('h:i A');
    }

    /**
     * Apply mazhab minute offsets to a prayer JSON array.
     * $prayerJson: ['text_en' => ..., 'start_time' => '05:22 AM', 'end_time' => '06:00 AM', ...]
     * $startOffsetField / $endOffsetField: field names on $mazhabSetting
     */
    private function applyOffset(
        ?array $prayerJson,
        MazhabWiseScheduleSetting $mazhabSetting,
        string $startOffsetField,
        string $endOffsetField
    ): ?array {
        if (empty($prayerJson)) {
            return $prayerJson;
        }

        $startOffset = (int) ($mazhabSetting->{$startOffsetField} ?? 0);
        $endOffset   = (int) ($mazhabSetting->{$endOffsetField}   ?? 0);

        if (isset($prayerJson['start_time']) && $startOffset !== 0) {
            $prayerJson['start_time'] = $this->adjustTime($prayerJson['start_time'], $startOffset);
        }
        if (isset($prayerJson['end_time']) && $endOffset !== 0) {
            $prayerJson['end_time'] = $this->adjustTime($prayerJson['end_time'], $endOffset);
        }

        return $prayerJson;
    }

    /**
     * Apply all mazhab offsets to a PermanentCalendar record and return as array.
     */
    private function applyMazhabOffsets(PermanentCalendar $calendar, MazhabWiseScheduleSetting $mazhabSetting): array
    {
        $data = $calendar->toArray();

        // Captured before offsets are applied: iftar carries its own offset and must not
        // inherit magrib's on top.
        $rawMagrib = isset($data['magrib']) && is_array($data['magrib']) ? $data['magrib'] : null;

        foreach (self::PRAYER_OFFSET_MAP as $column => [$startField, $endField]) {
            if (isset($data[$column]) && is_array($data[$column])) {
                $data[$column] = $this->applyOffset($data[$column], $mazhabSetting, $startField, $endField);
            }
        }

        $data['iftar'] = $this->deriveIftar($rawMagrib, $mazhabSetting);

        return $data;
    }

    /**
     * POST /api/permanent-calendar
     * Paginated calendar for a month, from a given day onwards.
     * Params: month_id (default: current month), to (page size, default: 10), mazhab_id (default: 1)
     */
    public function index(Request $request)
    {
        date_default_timezone_set('Asia/Dhaka');

        $today    = (int) date('d');
        $month    = (int) date('m');
        $monthId  = (int) $request->input('month_id', $month);
        $mazhabId = (int) $request->input('mazhab_id', 1);
        $pageSize = (int) $request->input('to', 10);

        $mazhabSetting = MazhabWiseScheduleSetting::where('mazhab_id', $mazhabId)->first();

        $query = PermanentCalendar::where('month_id', $monthId);

        if ($monthId === $month) {
            $query->whereRaw('CAST(day AS UNSIGNED) >= ?', [$today]);
        }

        $paginated = $query->orderByRaw('CAST(day AS UNSIGNED)')->paginate($pageSize);

        $items = collect($paginated->items())->map(function (PermanentCalendar $cal) use ($mazhabSetting) {
            return $mazhabSetting ? $this->applyMazhabOffsets($cal, $mazhabSetting) : $cal->toArray();
        });

        return response()->json([
            'status'      => 'success',
            'status_code' => 200,
            'today'       => $today,
            'message'     => 'Permanent Calendar Data',
            'data'        => [
                'mazhab_setting'      => $mazhabSetting,
                'permanent_calendars' => [
                    'data'          => $items,
                    'current_page'  => $paginated->currentPage(),
                    'last_page'     => $paginated->lastPage(),
                    'per_page'      => $paginated->perPage(),
                    'total'         => $paginated->total(),
                ],
            ],
        ]);
    }

    /**
     * GET /api/permanent-calendar/{month_id}
     * Full month prayer times.
     * Params: mazhab_id (default: 1)
     */
    public function byMonth(Request $request, int $monthId)
    {
        $mazhabId = (int) $request->input('mazhab_id', 1);

        $mazhabSetting = MazhabWiseScheduleSetting::where('mazhab_id', $mazhabId)->first();

        $calendars = PermanentCalendar::where('month_id', $monthId)
            ->orderByRaw('CAST(day AS UNSIGNED)')
            ->get();

        $items = $calendars->map(function (PermanentCalendar $cal) use ($mazhabSetting) {
            return $mazhabSetting ? $this->applyMazhabOffsets($cal, $mazhabSetting) : $cal->toArray();
        });

        return response()->json([
            'status'      => 'success',
            'status_code' => 200,
            'message'     => 'Monthly Calendar Data',
            'data'        => [
                'mazhab_setting'      => $mazhabSetting,
                'permanent_calendars' => $items,
            ],
        ]);
    }

    /**
     * GET /api/today-prayer
     * Today's full prayer schedule.
     * Params: mazhab_id (default: 1), day (optional, default: today), month_id (optional, default: current month)
     */
    public function today(Request $request)
    {
        date_default_timezone_set('Asia/Dhaka');

        $today    = (int) date('d');
        $month    = (int) date('m');
        $day      = (int) $request->input('day', $today);
        $monthId  = (int) $request->input('month_id', $month);
        $mazhabId = (int) $request->input('mazhab_id', 1);

        $mazhabSetting = MazhabWiseScheduleSetting::where('mazhab_id', $mazhabId)->first();

        $calendar = PermanentCalendar::where('month_id', $monthId)
            ->whereRaw('CAST(day AS UNSIGNED) = ?', [$day])
            ->first();

        if (!$calendar) {
            return response()->json([
                'status'      => 'error',
                'status_code' => 404,
                'message'     => 'No prayer data found for the requested date.',
            ], 404);
        }

        $prayerTimes = $mazhabSetting
            ? $this->applyMazhabOffsets($calendar, $mazhabSetting)
            : $calendar->toArray();

        return response()->json([
            'status'      => 'success',
            'status_code' => 200,
            'message'     => 'Prayer Schedule',
            'data'        => [
                'mazhab_setting' => $mazhabSetting,
                'day'            => $day,
                'month_id'       => $monthId,
                'prayer_times'   => $prayerTimes,
            ],
        ]);
    }

    /**
     * GET /api/ramazan-calendar
     * Next 30 days of sehri & iftar times from today (or a given date), handling month boundaries.
     * Params: mazhab_id (default: 1), day (optional), month_id (optional)
     */
    public function ramazanCalendar(Request $request)
    {
        date_default_timezone_set('Asia/Dhaka');

        $today    = (int) date('d');
        $month    = (int) date('m');
        $day      = (int) $request->input('day', $today);
        $monthId  = (int) $request->input('month_id', $month);
        $mazhabId = (int) $request->input('mazhab_id', 1);

        $mazhabSetting = MazhabWiseScheduleSetting::where('mazhab_id', $mazhabId)->first();

        // Build 30 days worth of (month_id, day) pairs, cycling months 1–12
        $days = [];
        $currentMonth = $monthId;
        $currentDay   = $day;

        for ($i = 0; $i < 30; $i++) {
            $days[] = ['month_id' => $currentMonth, 'day' => $currentDay];

            // Advance by one day — use Carbon to handle month-end correctly
            // We use a fixed leap year (2000) so Feb has 29 days as a safe upper bound
            $date = Carbon::createFromDate(2000, $currentMonth, $currentDay)->addDay();
            $currentDay   = (int) $date->day;
            $currentMonth = (int) $date->month;
        }

        // Group by month to minimise queries
        $grouped = collect($days)->groupBy('month_id');

        $results = collect();
        foreach ($grouped as $mId => $entries) {
            $dayNumbers = $entries->pluck('day')->toArray();

            $records = PermanentCalendar::where('month_id', $mId)
                ->whereIn(DB::raw('CAST(day AS UNSIGNED)'), $dayNumbers)
                ->orderByRaw('CAST(day AS UNSIGNED)')
                ->select('id', 'day', 'month_id', 'sehri', 'magrib')
                ->get();

            foreach ($records as $record) {
                $item = $record->toArray();

                // Derived from raw magrib, before magrib's own offset is applied.
                $rawMagrib = !empty($item['magrib']) && is_array($item['magrib'])
                    ? $item['magrib']
                    : null;

                if ($mazhabSetting) {
                    if (!empty($item['sehri'])) {
                        $item['sehri'] = $this->applyOffset(
                            $item['sehri'], $mazhabSetting, 'sehri_time', 'sehri_time'
                        );
                    }
                    if (!empty($item['magrib'])) {
                        $item['magrib'] = $this->applyOffset(
                            $item['magrib'], $mazhabSetting, 'magrib_time', 'magrib_time'
                        );
                    }
                }

                $item['iftar'] = $this->deriveIftar($rawMagrib, $mazhabSetting);

                $results->push($item);
            }
        }

        // Re-sort by month then day to maintain chronological order
        $sorted = $results->sortBy([
            fn ($a, $b) => $a['month_id'] <=> $b['month_id'],
            fn ($a, $b) => (int) $a['day'] <=> (int) $b['day'],
        ])->values();

        return response()->json([
            'status'      => 'success',
            'status_code' => 200,
            'message'     => 'Ramazan Calendar Data',
            'data'        => [
                'mazhab_setting'      => $mazhabSetting,
                'permanent_calendars' => $sorted,
            ],
        ]);
    }
}
