<?php

namespace App\Http\Controllers;

use App\Models\Masala;
use App\Models\MasalaCategory;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class MasalaController extends Controller
{
    use HelperTrait;

    private const PER_PAGE = 20;
    private const MAX_PER_PAGE = 100;

    /**
     * GET /api/masala-categories
     * Categories with a count, so the list screen needs one request.
     */
    public function categories()
    {
        $categories = MasalaCategory::where('status', true)
            ->withCount(['masalas' => fn ($q) => $q->where('status', true)])
            ->orderBy('sort_order')
            ->get();

        if ($categories->isEmpty()) {
            return $this->noContentResponse('Masala categories not found', [], 204);
        }

        return $this->successResponse('Masala categories retrieved successfully', $categories);
    }

    /**
     * GET /api/masala
     * Params: category_id, q (search), page, per_page
     */
    public function index(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|integer|exists:masala_categories,id',
            'q'           => 'nullable|string|min:2|max:100',
            'per_page'    => 'nullable|integer|min:1|max:' . self::MAX_PER_PAGE,
        ]);

        $perPage = min((int) $request->input('per_page', self::PER_PAGE) ?: self::PER_PAGE, self::MAX_PER_PAGE);

        $masalas = Masala::published()
            ->with('category:id,slug,name_en,name_bn,name_ar')
            ->when($request->filled('category_id'),
                fn ($q) => $q->where('masala_category_id', $request->input('category_id')))
            ->search($request->input('q'))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate($perPage)
            ->withQueryString();

        if ($masalas->isEmpty()) {
            return $this->noContentResponse('Masalas not found', [], 204);
        }

        return $this->successResponse('Masalas retrieved successfully', $masalas);
    }

    /**
     * GET /api/masala/{id}
     */
    public function show(int $id)
    {
        $masala = Masala::published()
            ->with('category:id,slug,name_en,name_bn,name_ar')
            ->find($id);

        if (!$masala) {
            return $this->notFoundResponse('Masala not found', []);
        }

        return $this->successResponse('Masala retrieved successfully', $masala);
    }
}
