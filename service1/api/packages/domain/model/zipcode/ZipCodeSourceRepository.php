<?php

namespace packages\domain\model\zipcode;
interface ZipCodeSourceRepository {
    public function get(): ZipCodeSource;
}
