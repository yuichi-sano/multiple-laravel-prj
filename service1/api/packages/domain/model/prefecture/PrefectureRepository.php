<?php

namespace packages\domain\model\prefecture;

interface PrefectureRepository
{
    public function list(): PrefectureList;

    public function findByCode(PrefectureCode $prefectureCode): Prefecture;

    public function findByName(PrefectureName $prefectureName): Prefecture;
}
