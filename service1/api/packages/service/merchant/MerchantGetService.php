<?php
namespace packages\service\merchant;
use packages\domain\model\merchant\Merchant;
use packages\domain\model\merchant\MerchantRepository;
use packages\service\helper\TransactionManagerInterface;

class MerchantGetService implements MerchantGetInterface
{
    private MerchantRepository $merchantRepository;
    private TransactionManagerInterface $transaction;

    public function __construct(MerchantRepository $merchantRepository, TransactionManagerInterface $transaction)
    {
        $this->merchantRepository = $merchantRepository;
        $this->transaction = $transaction;
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
