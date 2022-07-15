<?php

namespace packages\service\merchant;

use packages\domain\model\batch\MigrationBatchAudit;
use packages\domain\model\merchant\Merchant;

interface MerchantGetInterface
{
    /**
     * @param int $merchantId
     * @return Merchant
     */
    public function execute(int $merchantId): MigrationBatchAudit;

    public function getList(): array;
}
