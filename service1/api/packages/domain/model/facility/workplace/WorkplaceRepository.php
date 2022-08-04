<?php

namespace packages\domain\model\facility\workplace;

interface WorkplaceRepository
{
    public function list(): WorkplaceList;
}
