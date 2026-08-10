<?php

namespace App\Http\Controllers;

use App\Models\Hadith;
use App\Models\HadithBook;
use App\Models\HadithChapter;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class HadithController extends Controller
{
    use HelperTrait;

    private const PER_PAGE = 20;
    private const MAX_PER_PAGE = 100;

    private function perPage(Request $request): int
    {
        return min((int) $request->input('per_page', self::PER_PAGE) ?: self::PER_PAGE, self::MAX_PER_PAGE);
    }

    /**
     * GET /api/hadith-books
     */
    public function books()
    {
        $books = HadithBook::where('status', true)
            ->orderBy('sort_order')
            ->get();

        if ($books->isEmpty()) {
            return $this->noContentResponse('Hadith books not found', [], 204);
        }

        return $this->successResponse('Hadith books retrieved successfully', $books);
    }

    /**
     * GET /api/hadith-books/{bookId}/chapters
     */
    public function chapters(int $bookId)
    {
        $book = HadithBook::where('status', true)->find($bookId);

        if (!$book) {
            return $this->notFoundResponse('Hadith book not found', []);
        }

        $chapters = HadithChapter::where('hadith_book_id', $book->id)
            ->where('status', true)
            ->orderBy('chapter_no')
            ->get();

        if ($chapters->isEmpty()) {
            return $this->noContentResponse('Chapters not found', [], 204);
        }

        return $this->successResponse('Chapters retrieved successfully', [
            'book'     => $book,
            'chapters' => $chapters,
        ]);
    }

    /**
     * GET /api/hadith
     * Params: book_id, chapter_id, q (search), page, per_page
     */
    public function index(Request $request)
    {
        $request->validate([
            'book_id'    => 'nullable|integer|exists:hadith_books,id',
            'chapter_id' => 'nullable|integer|exists:hadith_chapters,id',
            'q'          => 'nullable|string|min:2|max:100',
            'per_page'   => 'nullable|integer|min:1|max:' . self::MAX_PER_PAGE,
        ]);

        $query = Hadith::published()
            ->with(['book:id,slug,name_en,name_bn,name_ar', 'chapter:id,chapter_no,name_en,name_bn,name_ar'])
            ->when($request->filled('book_id'), fn ($q) => $q->where('hadith_book_id', $request->input('book_id')))
            ->when($request->filled('chapter_id'), fn ($q) => $q->where('hadith_chapter_id', $request->input('chapter_id')))
            ->search($request->input('q'))
            ->orderBy('hadith_book_id')
            ->orderBy('hadith_number');

        $hadiths = $query->paginate($this->perPage($request))->withQueryString();

        if ($hadiths->isEmpty()) {
            return $this->noContentResponse('Hadiths not found', [], 204);
        }

        return $this->successResponse('Hadiths retrieved successfully', $hadiths);
    }

    /**
     * GET /api/hadith/{id}
     */
    public function show(int $id)
    {
        $hadith = Hadith::published()
            ->with(['book:id,slug,name_en,name_bn,name_ar', 'chapter:id,chapter_no,name_en,name_bn,name_ar'])
            ->find($id);

        if (!$hadith) {
            return $this->notFoundResponse('Hadith not found', []);
        }

        return $this->successResponse('Hadith retrieved successfully', $hadith);
    }

    /**
     * GET /api/hadith-random
     * Backs a "hadith of the day" card.
     */
    public function random()
    {
        $hadith = Hadith::published()
            ->with(['book:id,slug,name_en,name_bn,name_ar', 'chapter:id,chapter_no,name_en,name_bn,name_ar'])
            ->inRandomOrder()
            ->first();

        if (!$hadith) {
            return $this->noContentResponse('Hadiths not found', [], 204);
        }

        return $this->successResponse('Hadith retrieved successfully', $hadith);
    }
}
