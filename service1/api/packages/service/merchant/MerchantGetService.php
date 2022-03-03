<?php
namespace packages\service\merchant;
use App\Extension\Support\Facades\TransactionManager;
use packages\domain\model\merchant\Merchant;
use packages\domain\model\merchant\MerchantRepository;
use packages\domain\model\zipcode\ZipCodeFactory;
use packages\domain\model\zipcode\ZipCodeList;
use packages\domain\model\zipcode\ZipCodeSourceRepository;
use packages\service\helper\TransactionManagerInterface;

class MerchantGetService implements MerchantGetInterface
{
    private MerchantRepository $merchantRepository;
    private ZipCodeSourceRepository $zipCodeSourceRepository;

    public function __construct(MerchantRepository $merchantRepository, ZipCodeSourceRepository $zipCodeSourceRepository)
    {
        $this->merchantRepository = $merchantRepository;
        $this->zipCodeSourceRepository = $zipCodeSourceRepository;

    }

    public function execute(int $merchantId): Merchant
    {
        return $this->merchantRepository->findMerchant($merchantId);
    }

    public function getList(): array
    {
        //$this->transaction->startTransaction('hoge');
        $zipCodeSource = $this->zipCodeSourceRepository->get();
        $file= $zipCodeSource->toFile();
        $needContinue=false;
        $mergeRows=[];
        $list = new ZipCodeList();
        //$file->seek(PHP_INT_MAX);
        //var_dump($file->key()+1);
        foreach ($file  as $key=>$row){
            mb_convert_variables('UTF-8', 'SJIS', $row);
            $mergeRows[] = $row;

            $factory = new ZipCodeFactory();
            $isUnClose  = $factory->isUnClose($row);
            $isClose =$factory->isClose($row);
            if(($isUnClose || $needContinue) && !$isClose){
                $needContinue = true;
                continue;
            }
            if($isClose){
                $needContinue =false;
            }

            $mergedRow = $factory->mergeRows($mergeRows);
            if($factory->needSplit($mergedRow)){
                foreach ($factory->splitRow($mergedRow)as $splitRow){
                    $zipCode = $factory->create($splitRow);
                    $list->add($zipCode);
                }
            }else{

                $zipCode      = $factory->create($mergedRow);
                $townArea     = $zipCode->getTownArea();
                $townAreaKana = $zipCode->getTownAreaKana();

                $zipCode->setTownAreaKana(
                    $factory->cleanCantSplitTownAreaKana($townArea, $townAreaKana)
                );
                $zipCode->setTownArea($factory->cleanCantSplitTownArea($townArea));

                $list->add($zipCode);

            }
            $mergeRows = [];
        }

        var_dump($list->count());
        exit;



        return $this->merchantRepository->list();
    }

}
