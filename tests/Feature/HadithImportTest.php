<?php

namespace Tests\Feature;

use App\Models\Hadith;
use App\Models\HadithBook;
use App\Models\HadithChapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Sleep;
use Tests\TestCase;

/**
 * The importer is exercised against faked HTTP responses shaped exactly like the
 * fawazahmed0/hadith-api payloads, so the join logic is covered without a 34k-row
 * network download in CI.
 */
class HadithImportTest extends TestCase
{
    use RefreshDatabase;

    private function edition(string $name, array $texts, array $sections, array $grades = []): array
    {
        $hadiths = [];

        foreach ($texts as $number => $text) {
            $hadiths[] = [
                'hadithnumber' => $number,
                'arabicnumber' => $number,
                'text'         => $text,
                'grades'       => $grades[$number] ?? [],
                'reference'    => ['book' => $number <= 2 ? 1 : 2, 'hadith' => $number],
            ];
        }

        return [
            'metadata' => [
                'name'     => $name,
                'sections' => $sections,
                'section_details' => [
                    1 => ['hadithnumber_first' => 1, 'hadithnumber_last' => 2],
                    2 => ['hadithnumber_first' => 3, 'hadithnumber_last' => 3],
                ],
            ],
            'hadiths' => $hadiths,
        ];
    }

    private function fakeBukhari(): void
    {
        Http::fake([
            '*ara-bukhari*' => Http::response($this->edition('Sahih al Bukhari',
                [1 => 'النية', 2 => 'الوحي', 3 => 'الإيمان'],
                [0 => '', 1 => 'الوحي', 2 => 'الإيمان'])),

            '*ben-bukhari*' => Http::response($this->edition('Sahih al Bukhari',
                [1 => 'নিয়্যত', 2 => 'ওহী', 3 => 'ঈমান'],
                [0 => '', 1 => 'ওহীর সূচনা', 2 => 'ঈমান'])),

            '*eng-bukhari*' => Http::response($this->edition('Sahih al Bukhari',
                [1 => 'Actions are by intentions', 2 => 'Revelation', 3 => 'Faith'],
                [0 => '', 1 => 'Revelation', 2 => 'Belief'],
                [1 => [['name' => 'Muhammad al-Bukhari', 'grade' => 'Sahih']]])),

            '*' => Http::response([], 404),
        ]);
    }

    public function test_import_creates_book_chapters_and_hadiths(): void
    {
        $this->fakeBukhari();

        $this->artisan('hadith:import', ['--book' => 'bukhari'])->assertSuccessful();

        $book = HadithBook::where('slug', 'bukhari')->first();

        $this->assertNotNull($book);
        $this->assertSame('Sahih al-Bukhari', $book->name_en);
        $this->assertSame('সহীহ বুখারী', $book->name_bn);
        $this->assertSame('صحيح البخاري', $book->name_ar);
        $this->assertSame(3, $book->total_hadiths);

        $this->assertSame(2, HadithChapter::where('hadith_book_id', $book->id)->count());
        $this->assertSame(3, Hadith::where('hadith_book_id', $book->id)->count());
    }

    public function test_the_three_language_editions_are_joined_by_hadith_number(): void
    {
        $this->fakeBukhari();
        $this->artisan('hadith:import', ['--book' => 'bukhari'])->assertSuccessful();

        $first = Hadith::where('hadith_number', 1)->first();

        $this->assertSame('النية', $first->arabic_text);
        $this->assertSame('নিয়্যত', $first->bangla_text);
        $this->assertSame('Actions are by intentions', $first->english_text);
    }

    public function test_chapter_names_come_from_their_own_edition(): void
    {
        $this->fakeBukhari();
        $this->artisan('hadith:import', ['--book' => 'bukhari'])->assertSuccessful();

        $chapter = HadithChapter::where('chapter_no', 1)->first();

        $this->assertSame('Revelation', $chapter->name_en);
        $this->assertSame('ওহীর সূচনা', $chapter->name_bn);
        $this->assertSame('الوحي', $chapter->name_ar);
    }

    public function test_hadiths_are_linked_to_the_right_chapter(): void
    {
        $this->fakeBukhari();
        $this->artisan('hadith:import', ['--book' => 'bukhari'])->assertSuccessful();

        $chapterOne = HadithChapter::where('chapter_no', 1)->first();
        $chapterTwo = HadithChapter::where('chapter_no', 2)->first();

        // reference.book drives the mapping: hadiths 1-2 -> chapter 1, hadith 3 -> chapter 2
        $this->assertSame(2, Hadith::where('hadith_chapter_id', $chapterOne->id)->count());
        $this->assertSame(1, Hadith::where('hadith_chapter_id', $chapterTwo->id)->count());
        $this->assertSame(2, $chapterOne->fresh()->total_hadiths);
        $this->assertSame(1, $chapterTwo->fresh()->total_hadiths);
    }

    public function test_grades_are_captured(): void
    {
        $this->fakeBukhari();
        $this->artisan('hadith:import', ['--book' => 'bukhari'])->assertSuccessful();

        $this->assertSame('Sahih (Muhammad al-Bukhari)', Hadith::where('hadith_number', 1)->first()->grade);
        $this->assertNull(Hadith::where('hadith_number', 2)->first()->grade);
    }

    public function test_import_is_idempotent(): void
    {
        $this->fakeBukhari();

        $this->artisan('hadith:import', ['--book' => 'bukhari'])->assertSuccessful();
        $this->artisan('hadith:import', ['--book' => 'bukhari'])->assertSuccessful();

        $this->assertSame(3, Hadith::count());
        $this->assertSame(2, HadithChapter::count());
        $this->assertSame(1, HadithBook::count());
    }

    public function test_unknown_book_is_rejected(): void
    {
        $this->artisan('hadith:import', ['--book' => 'nonsense'])->assertFailed();

        $this->assertSame(0, HadithBook::count());
    }

    public function test_a_failed_download_aborts_without_partial_data(): void
    {
        Sleep::fake();   // otherwise the retry backoff really sleeps for 6 seconds
        Http::fake(['*' => Http::response([], 503)]);

        $this->artisan('hadith:import', ['--book' => 'bukhari'])->assertFailed();

        $this->assertSame(0, HadithBook::count());
        $this->assertSame(0, Hadith::count());
    }
}
