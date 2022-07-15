<?php

namespace App\Http\Controllers\Sample;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Requests\Sample\SampleRequest;
use App\Http\Resources\Sample\SampleResource;
use packages\domain\model\batch\MigrationBatchAuditApplyDate;
use packages\domain\model\campaign\CampaignCriteria;
use packages\domain\model\campaign\CampaignId;
use packages\domain\model\point\add\basis\number\PointAddNumber;
use packages\domain\model\point\add\basis\number\PointAddNumberId;
use packages\domain\model\point\add\basis\number\PointAddNumberValue;
use packages\domain\model\point\add\basis\PointAddBasis;
use packages\domain\model\point\add\basis\PointAddEndDate;
use packages\domain\model\point\add\basis\PointAddNumberBasis;
use packages\domain\model\point\add\basis\PointAddStartDate;
use packages\domain\model\point\comment\PointComment;
use packages\domain\model\point\comment\PointCommentBody;
use packages\domain\model\point\comment\PointCommentId;
use packages\domain\model\user\UserId;
use packages\domain\model\zipcode\ZipCodePostalCode;
use packages\service\campaign\CampaignGetService;
use packages\service\campaign\LotteryGetService;
use packages\service\facility\DeviceGetService;
use packages\service\facility\FacilityGetService;
use packages\service\point\PointGetService;
use packages\service\jp\ZipCodeGetInterface;
use packages\service\jp\ZipCodeMigrationApplyInterface;
use packages\service\merchant\MerchantGetInterface;
use packages\service\UserGetInterface;

class SampleController extends BaseController
{
    private MerchantGetInterface $merchantGet;
    private UserGetInterface $userGet;
    private ZipCodeMigrationApplyInterface $zipCodeMigrationApply;
    private CampaignGetService $campaignGetService;
    private PointGetService $pointGetService;
    private ZipCodeGetInterface $zipCodeGet;
    private FacilityGetService $facilityGetService;
    private DeviceGetService $deviceGetService;
    private LotteryGetService $lotteryGetService;

    public function __construct(
        MerchantGetInterface $merchantGet,
        UserGetInterface $userGet,
        ZipCodeMigrationApplyInterface $zipCodeMigrationApply,
        CampaignGetService $campaignGetService,
        ZipCodeGetInterface $zipCodeGet,
        PointGetService $pointGetService,
        FacilityGetService $facilityGetService,
        DeviceGetService $deviceGetService,
        LotteryGetService $lotteryGetService,
    ) {
        $this->merchantGet = $merchantGet;
        $this->userGet = $userGet;
        $this->zipCodeMigrationApply = $zipCodeMigrationApply;
        $this->campaignGetService = $campaignGetService;
        $this->zipCodeGet = $zipCodeGet;
        $this->pointGetService = $pointGetService;
        $this->facilityGetService = $facilityGetService;
        $this->deviceGetService = $deviceGetService;
        $this->lotteryGetService = $lotteryGetService;
    }

    /**
     * test
     * @param mixed
     */
    public function index(SampleRequest $request)
    {
        //
        $userId = new UserId(1);
        //$response = $this->userGet->execute($userId);
        //$response = $this->merchantGet->execute(1);

        //$response = $this->merchantGet->execute(1);
        //$response=$this->zipCodeMigrationApply->getSource();


        //$response=$this->zipCodeMigrationApply->apply(new UserId('140'),MigrationBatchAuditApplyDate::create('2022-04-01 12:00:00'));
        //$response=$this->zipCodeGet->findLatestMigration();
        //$response=$this->zipCodeGet->findLatestUpdate();
//
        //var_dump($response);
        //exit;
        //$criteria  = CampaignCriteria::create(
        //    null,
        //                  '2019-11-01 00:00:00',
        //                  '2023-02-10 23:59:59',
        //                        1,
        //                        10,
        //);
//
        //$response =$this->campaignGetService->getCampaignList($criteria);
        //$count =$this->campaignGetService->getCampaignListCount($criteria);
        //foreach ($response as $campaign){
        //    if($campaign->isLottery()){
        //        $lotteryItems =$this->campaignGetService->getLotteryCampaign($campaign->getId());
        //        foreach ($lotteryItems as $lotteryItem) {
        //            $lotteryItem->setPresentItemList($this->campaignGetService->getLotteryPresent($campaign->getId(), $lotteryItem->getId()));
//
        //        }
        //    }
        //}
        //$campaignId = new CampaignId(22);
//
        ////$test = $this->lotteryGetService->getLotteryList($campaignId);
//
//
        ////var_dump($lotteryItem);
//
        ////$pref = $this->zipCodeMigrationApply->getPrefectureList();
        //$test = $this->zipCodeGet->findAddress(new ZipCodePostalCode('8910614'));
        //var_dump($test);
        //exit;
//
        ////$r = $this->pointGetService->getPointAddBasis(new CampaignId(10));
//
        //$pointAddBasis=new PointAddNumberBasis(
        //    new PointAddNumber(new PointAddNumberId(),new PointAddNumberValue(10),true),
        //    new PointComment(new PointCommentId(50), new PointCommentBody()),
        //    PointAddStartDate::create('2022-04-01 00:00:00'),
        //    PointAddEndDate::create('2025-04-01 00:00:00'),
        //    new UserId('140')
        //);
//
        //$pointCommentList = $this->pointGetService->getPointCommentList();
        //$workplace = $this->facilityGetService->getWorkplaceList();
        //$type= $this->deviceGetService->getHTDeviceTypeList();
        ////var_dump($pointAddBasis);
        ////var_dump($workplace);
//
//
        ////$l=$this->pointGetService->addPointAddBasis($pointAddBasis);
        ////var_dump($r);
        ////var_dump($l);
        ////var_dump($type);
        //exit;
//
        ////var_dump($response);
        ////$response = $this->merchantGet->getList();
        ////var_dump($response);
        ////$response = $this->userGet->execute($userId);
//
        return SampleResource::buildResult($response);
    }
}
