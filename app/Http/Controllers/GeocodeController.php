<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Reverse geocoding, proxied.
 *
 * The app called the Google Geocoding API directly from the client with the key inlined
 * in the bundle, where anyone could extract it. The key now lives only on the server.
 *
 * As a side effect the server can match the geocoded place against the districts table
 * and hand back a district_id, which is what actually drives prayer times — so the app
 * can suggest a district instead of asking the user to find it in a list.
 */
class GeocodeController extends Controller
{
    use HelperTrait;

    /** Results are stable for a given place, so they are cached rather than re-billed. */
    private const CACHE_TTL = 60 * 60 * 24 * 30;

    /**
     * GET /api/geocode?lat=&lng=
     */
    public function reverse(Request $request)
    {
        $validated = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $lat = round((float) $validated['lat'], 3);   // ~110m, plenty for a district
        $lng = round((float) $validated['lng'], 3);

        $key = config('services.google_maps.key');

        if (!$key) {
            return $this->errorResponse('Geocoding is not configured on the server', [], 503);
        }

        $payload = Cache::remember("geocode:{$lat},{$lng}", self::CACHE_TTL, function () use ($lat, $lng, $key) {
            $response = Http::timeout(10)->retry(2, 500, throw: false)
                ->get('https://maps.googleapis.com/maps/api/geocode/json', [
                    'latlng' => "{$lat},{$lng}",
                    'key'    => $key,
                ]);

            if (!$response->successful()) {
                return null;
            }

            return $this->extract($response->json());
        });

        if (!$payload) {
            return $this->errorResponse('Could not resolve that location', [], 502);
        }

        return $this->successResponse('Location resolved successfully', $payload);
    }

    /**
     * Pull the city and division out of a Google response, then try to match a district.
     * Not every coordinate has a locality or an administrative area, so both are optional.
     */
    private function extract(array $body): ?array
    {
        $components = $body['results'][0]['address_components'] ?? null;

        if (!$components) {
            return null;
        }

        $find = function (string $type) use ($components): ?string {
            foreach ($components as $component) {
                if (in_array($type, $component['types'] ?? [], true)) {
                    return $component['long_name'] ?? null;
                }
            }
            return null;
        };

        $city     = $find('locality');
        $division = $find('administrative_area_level_1');
        // Google labels Bangladeshi districts as level 2.
        $areaTwo  = $find('administrative_area_level_2');

        return [
            'city'     => $city,
            'division' => $division,
            'district' => $this->matchDistrict($areaTwo, $city),
        ];
    }

    /**
     * Match a geocoded name against the districts table.
     *
     * Google uses current spellings (Chattogram, Cumilla, Jashore) while the table holds
     * older ones (Chittagong, Comilla, Jessore), so a few aliases are mapped explicitly.
     */
    private const ALIASES = [
        'chattogram' => 'Chittagong',
        'cumilla'    => 'Comilla',
        'jashore'    => 'Jessore',
        'bogura'     => 'Bogra',
        'barishal'   => 'Barisal',
        'chapainawabganj' => 'Nawabganj',
        'chapai nawabganj' => 'Nawabganj',
        'khagrachhari' => 'Khagrachari',
    ];

    private function matchDistrict(?string ...$candidates): ?array
    {
        foreach (array_filter($candidates) as $candidate) {
            $clean = trim(preg_replace('/\s+(district|zila|zilla)$/i', '', $candidate));
            $name  = self::ALIASES[mb_strtolower($clean)] ?? $clean;

            $district = District::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->first();

            if ($district) {
                return [
                    'id'       => $district->id,
                    'name'     => $district->name,
                    'division' => $district->division?->name,
                ];
            }
        }

        return null;
    }
}
