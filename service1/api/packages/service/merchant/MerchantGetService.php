<?php
namespace packages\service\merchant;
use App\Extension\Support\Facades\TransactionManager;
use packages\domain\model\merchant\Merchant;
use packages\domain\model\merchant\MerchantRepository;
use packages\service\helper\TransactionManagerInterface;

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
        //$this->transaction->startTransaction('hoge');
        return $this->merchantRepository->list();
    }

}
