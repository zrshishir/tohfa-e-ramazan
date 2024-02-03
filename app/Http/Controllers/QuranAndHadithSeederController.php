<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuranAndHadithSeederController extends Controller
{
    protected $englishApiEndpoint = 'https://quranenc.com/api/v1/translation/aya/english_rwwad';
    protected $banglaApiEndpoint  = 'https://quranenc.com/api/v1/translation/aya/bengali_zakaria';

    public function index()
    {

        $suraNo   = request()->sura_no;
        $fromAyat = request()->from_ayat;
        $toAyat   = request()->to_ayat;

        $englishApiEndpoint = $this->englishApiEndpoint . "/{$suraNo}/{$fromAyat}/{$toAyat}";
        $banglaApiEndpoint  = $this->banglaApiEndpoint . "/{$suraNo}/{$fromAyat}/{$toAyat}";

        $client   = new \GuzzleHttp\Client();
        $response = $client->request('GET', $banglaApiEndpoint);

        // write a curl request to get the data from the api

        return ($response);
    }
}
