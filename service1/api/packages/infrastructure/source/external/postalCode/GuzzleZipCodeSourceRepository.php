<?php

namespace packages\infrastructure\source\external\postalCode;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use packages\domain\model\zipcode\ZipCodeSource;
use packages\domain\model\zipcode\ZipCodeSourceRepository;

class GuzzleZipCodeSourceRepository implements ZipCodeSourceRepository
{
    public function  get(): ZipCodeSource
    {
        // 重複しない保存先を取得 /tmp
        $tempName = tempnam(sys_get_temp_dir(), 'postal_cd_');
        $response = Http::sink($tempName)
            ->post('https://www.post.japanpost.jp/zipcode/dl/kogaki/zip/ken_all.zip');
        // localに保存
//        /var_dump($response);


        Storage::disk('local')->put('test.zip', $response->body());

        $zip = new \ZipArchive();
        if ($zip->open(Storage::path('test.zip')) === true) {
            $zip->extractTo(Storage::path('/extra'));
            $zip->close();
        }
        return new ZipCodeSource(Storage::path('/extra/KEN_ALL.csv'));
        //return new ZipCodeSource(Storage::path('/extra/26KYOUTO.csv'));

        //return $response;
    }

}
