<?php

declare(strict_types=1);

namespace packages\domain\model\prefecture;

class Prefecture
{
    private PrefectureCode $prefectureCode;
    private PrefectureName $prefectureName;

    public function __construct(
        PrefectureCode $prefectureCode,
        PrefectureName $prefectureName,
    ) {
        $this->prefectureCode = $prefectureCode;
        $this->prefectureName = $prefectureName;
    }

    /**
     * @return PrefectureCode
     */
    public function getId(): PrefectureCode
    {
        return $this->prefectureCode;
    }

    /**
     * @return PrefectureName
     */
    public function getPrefectureName(): PrefectureName
    {
        return $this->prefectureName;
    }

    public array $collectionKeys = [
        'prefectureCode'
    ];
}
