<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

/**
 * Bookmarks belong to the signed-in user. Every route here is behind auth:sanctum —
 * guests keep their bookmarks on the device, which is why the app must not require an
 * account to use them.
 */
class BookmarkController extends Controller
{
    use HelperTrait;

    /**
     * GET /api/bookmarks
     */
    public function index(Request $request)
    {
        $bookmarks = Bookmark::where('user_id', $request->user()->id)
            ->with([
                'ayat:id,sura_id,ayat_no,arabic_text,bangla_text,english_text',
                'sura:id,name,arabic_name',
            ])
            ->latest()
            ->get();

        if ($bookmarks->isEmpty()) {
            return $this->noContentResponse('No bookmarks yet', [], 204);
        }

        return $this->successResponse('Bookmarks retrieved successfully', $bookmarks);
    }

    /**
     * POST /api/bookmarks
     * Idempotent: bookmarking the same ayat twice updates rather than duplicating.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ayat_id' => 'required|integer|exists:ayats,id',
            'sura_id' => 'nullable|integer|exists:suras,id',
            'ayat_no' => 'nullable|integer|min:1',
            'page'    => 'nullable|integer|min:1',
        ]);

        $bookmark = Bookmark::updateOrCreate(
            ['user_id' => $request->user()->id, 'ayat_id' => $validated['ayat_id']],
            [
                'sura_id' => $validated['sura_id'] ?? null,
                'ayat_no' => $validated['ayat_no'] ?? null,
                'page'    => $validated['page'] ?? null,
            ]
        );

        return $this->successResponse('Bookmark saved', $bookmark, 201);
    }

    /**
     * DELETE /api/bookmarks/{ayatId}
     * Keyed by ayat rather than bookmark id, so the reader can toggle without first
     * looking up which bookmark row it created.
     */
    public function destroy(Request $request, int $ayatId)
    {
        $deleted = Bookmark::where('user_id', $request->user()->id)
            ->where('ayat_id', $ayatId)
            ->delete();

        if (!$deleted) {
            return $this->notFoundResponse('Bookmark not found', []);
        }

        return $this->successResponse('Bookmark removed', []);
    }

    /**
     * POST /api/bookmarks/sync
     *
     * Merges the device's bookmarks into the account on sign-in, rather than replacing
     * either side. Someone who read on a phone before creating an account should not
     * lose those bookmarks, and someone signing in on a second device should not wipe
     * what is already on the account.
     */
    public function sync(Request $request)
    {
        $validated = $request->validate([
            'bookmarks'           => 'present|array',
            'bookmarks.*.ayat_id' => 'required|integer|exists:ayats,id',
            'bookmarks.*.sura_id' => 'nullable|integer|exists:suras,id',
            'bookmarks.*.ayat_no' => 'nullable|integer|min:1',
            'bookmarks.*.page'    => 'nullable|integer|min:1',
        ]);

        $userId = $request->user()->id;

        foreach ($validated['bookmarks'] as $incoming) {
            Bookmark::updateOrCreate(
                ['user_id' => $userId, 'ayat_id' => $incoming['ayat_id']],
                [
                    'sura_id' => $incoming['sura_id'] ?? null,
                    'ayat_no' => $incoming['ayat_no'] ?? null,
                    'page'    => $incoming['page'] ?? null,
                ]
            );
        }

        // The merged set goes back, so the device can adopt it wholesale.
        $merged = Bookmark::where('user_id', $userId)
            ->with([
                'ayat:id,sura_id,ayat_no,arabic_text,bangla_text,english_text',
                'sura:id,name,arabic_name',
            ])
            ->latest()
            ->get();

        return $this->successResponse('Bookmarks synced', $merged);
    }
}
