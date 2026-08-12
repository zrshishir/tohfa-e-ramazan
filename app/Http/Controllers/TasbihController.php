<?php

namespace App\Http\Controllers;

use App\Models\Tasbih;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class TasbihController extends Controller
{
    use HelperTrait;

    /**
     * Until accounts exist, every device shares the seeded default row.
     * Requests may pass ?user_id= to target a specific row.
     */
    private const DEFAULT_USER_ID = 1;

    /**
     * Validation rules for a tasbih payload.
     *
     * `tasbih` is a JSON array of dhikr objects — not a set of columns. The previous
     * rules validated six invented field names (`subhanallah`, `alhamdulillah`, ...)
     * that exist neither in the table nor in the model, so no valid request could
     * ever have been saved.
     */
    private function rules(bool $requireUser = true): array
    {
        return [
            'user_id'                 => ($requireUser ? 'required' : 'sometimes') . '|integer|exists:users,id',
            'tasbih'                  => 'required|array|min:1',
            'tasbih.*.text_en'        => 'required|string',
            'tasbih.*.text_bn'        => 'nullable|string',
            'tasbih.*.text_ar'        => 'nullable|string',
            'tasbih.*.reset_on'       => 'nullable|integer|min:0',
            'tasbih.*.count'          => 'nullable|integer|min:0',
            'tasbih.*.today_count'    => 'nullable|integer|min:0',
            'tasbih.*.monthly_count'  => 'nullable|integer|min:0',
            'tasbih.*.yearly_count'   => 'nullable|integer|min:0',
            'tasbih.*.total_count'    => 'nullable|integer|min:0',
        ];
    }

    /**
     * Whose tasbih this request concerns.
     *
     * A signed-in user always wins: once there is a token, the URL or query string must
     * not be able to point at somebody else's counters. Guests fall back to the shared
     * seeded row, as before.
     */
    private function resolveUserId(Request $request, ?int $fallback = null): int
    {
        return $request->user()?->id
            ?? $fallback
            ?? (int) $request->input('user_id', self::DEFAULT_USER_ID);
    }

    /**
     * GET /api/tasbih
     * Params: user_id (optional, defaults to 1). Ignored when signed in.
     */
    public function index(Request $request)
    {
        $userId = $this->resolveUserId($request);

        $tasbih = Tasbih::where('user_id', $userId)->first();

        if (!$tasbih) {
            return $this->notFoundResponse('Tasbih not found for this user', []);
        }

        return $this->successResponse('Tasbih retrieved successfully', $tasbih);
    }

    /**
     * GET /api/tasbih/{userId}
     */
    public function show(int $userId)
    {
        $tasbih = Tasbih::where('user_id', $userId)->first();

        if (!$tasbih) {
            return $this->notFoundResponse('Tasbih not found for this user', []);
        }

        return $this->successResponse('Tasbih retrieved successfully', $tasbih);
    }

    /**
     * POST /api/tasbih
     * Creates the user's tasbih row, or overwrites it if one already exists.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $tasbih = Tasbih::updateOrCreate(
            ['user_id' => $validated['user_id']],
            ['tasbih'  => $validated['tasbih']]
        );

        return $this->successResponse('Tasbih saved successfully', $tasbih);
    }

    /**
     * PUT /api/tasbih/{userId}
     * Persists updated counters for the given user.
     */
    public function update(Request $request, int $userId)
    {
        $userId = $this->resolveUserId($request, $userId);

        $tasbih = Tasbih::where('user_id', $userId)->first();

        if (!$tasbih) {
            return $this->notFoundResponse('Tasbih not found for this user', []);
        }

        $validated = $request->validate($this->rules(requireUser: false));

        $tasbih->update(['tasbih' => $validated['tasbih']]);

        return $this->successResponse('Tasbih updated successfully', $tasbih->fresh());
    }

    /**
     * POST /api/tasbih/sync
     *
     * Merges the device's counters into the signed-in account on login. Counters are
     * merged by taking the higher of each value rather than overwriting: a dhikr count
     * only ever goes up, and neither the device's nor the account's progress should be
     * thrown away because of which one synced last.
     */
    public function sync(Request $request)
    {
        $validated = $request->validate($this->rules(requireUser: false));

        $userId = $request->user()->id;
        $stored = Tasbih::where('user_id', $userId)->first();

        $existing = collect($stored?->tasbih ?? [])->keyBy('text_en');

        $merged = collect($validated['tasbih'])->map(function (array $incoming) use ($existing) {
            $current = $existing->get($incoming['text_en'], []);

            foreach (['count', 'today_count', 'monthly_count', 'yearly_count', 'total_count'] as $counter) {
                $incoming[$counter] = max(
                    (int) ($incoming[$counter] ?? 0),
                    (int) ($current[$counter] ?? 0)
                );
            }

            return $incoming;
        })->values()->all();

        $tasbih = Tasbih::updateOrCreate(['user_id' => $userId], ['tasbih' => $merged]);

        return $this->successResponse('Tasbih synced', $tasbih);
    }

    /**
     * DELETE /api/tasbih/{userId}
     */
    public function destroy(int $userId)
    {
        $tasbih = Tasbih::where('user_id', $userId)->first();

        if (!$tasbih) {
            return $this->notFoundResponse('Tasbih not found for this user', []);
        }

        $tasbih->delete();

        return $this->successResponse('Tasbih deleted successfully', []);
    }
}
