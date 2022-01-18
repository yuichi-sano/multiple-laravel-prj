<?php
namespace packages\service\merchant;
use packages\domain\model\merchant\Merchant;
interface MerchantGetInterface
{
/**
* @param int $merchantId
* @return Merchant
*/
public function execute(int $merchantId): Merchant;
public function getList(): array;
}
