<?php
namespace packages\service\merchant;
use packages\domain\model\merchant\Merchant;
use packages\domain\model\merchant\MerchantRepository;

class MerchantGetService implements MerchantGetInterface
{
    private MerchantRepository $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
    }

    public function execute(int $merchantId): Merchant
    {
        return $this->merchantRepository->findMerchant($merchantId);
    }

    public function getList(): array
    {
        return $this->merchantRepository->list();
    }

}
