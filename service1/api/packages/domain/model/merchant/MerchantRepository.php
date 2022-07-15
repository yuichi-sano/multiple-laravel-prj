<?php

namespace packages\domain\model\merchant;

interface MerchantRepository
{
    public function findMerchant(int $merchantId): Merchant;

    public function list(): array;
}
