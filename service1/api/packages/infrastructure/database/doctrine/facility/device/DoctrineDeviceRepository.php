<?php

declare(strict_types=1);

namespace packages\infrastructure\database\doctrine\facility\device;

use App\Exceptions\ErrorCodeConst;
use App\Exceptions\WebAPIException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Mapping\SqlResultSetMappings;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use packages\domain\model\facility\device\criteria\DeviceCriteria;
use packages\domain\model\facility\device\Device;
use packages\domain\model\facility\device\DeviceId;
use packages\domain\model\facility\device\DeviceIpAddress;
use packages\domain\model\facility\device\DeviceList;
use packages\domain\model\facility\device\DeviceRepository;
use packages\infrastructure\database\doctrine\DoctrineRepository;
use packages\infrastructure\database\sql\SqlBladeParams;

class DoctrineDeviceRepository extends DoctrineRepository implements DeviceRepository
{
    protected function getParentDir(): string
    {
        return implode(
            '/',
            array_diff(
                explode('/', dirname(__FILE__)),
                explode('/', doctrine_repo_path())
            )
        );
    }

    public function list(DeviceCriteria $criteria): DeviceList
    {
        $rsm = new ResultSetMapping();

        $rsm->addScalarResult('id', 'delivery_device_id');
        $rsm->addScalarResult('computer_name', 'delivery_device_name');
        $rsm->addScalarResult('label', 'delivery_device_label');
        $rsm->addScalarResult('ip_address', 'delivery_device_ip_address');
        $rsm->addScalarResult('location_memo', 'delivery_device_location');
        $rsm->addScalarResult('job_work_place_id', 'workplace_workplace_id');
        $rsm->addScalarResult('pacsystem_user_id', 'pacsystem_user_id');
        $rsm->addScalarResult('delivery_work_place_company_name', 'delivery_workplace_company_name');
        $rsm->addScalarResult('delivery_work_place_name', 'delivery_workplace_name');
        $rsm->addScalarResult('delivery_work_place_code', 'delivery_workplace_code');
        $rsm->addScalarResult('delivery_work_place_code_s', 'delivery_workplace_code_s');
        $rsm->addScalarResult('ht_device_id', 'h_t_device__h_t_device_id');
        $rsm->addScalarResult('ht_device_ip_address', 'h_t_device__h_t_device_ip_address');
        $rsm->addScalarResult('ht_device_location_memo', 'h_t_device__h_t_device_location_memo');
        $rsm->addScalarResult('ht_device_type', 'h_t_device__h_t_device_type');

        $blade = new SqlBladeParams($criteria);
        $sql = $this->readNativeQueryFile('deviceList', $blade->toFormat());
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        try {
            $list = new DeviceList($this->getGroupingResult($query->getResult()));
            return $list->paging($criteria->pageable->getOffset(), $criteria->pageable->getPerPage());
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    public function listCount(DeviceCriteria $criteria): int
    {
        $rsm = new ResultSetMapping();

        $rsm->addScalarResult('count', 'count');

        $blade = new SqlBladeParams($criteria);
        $sql = $this->readNativeQueryFile('deviceListCount', $blade->toFormat());
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        try {
            return $query->getSingleScalarResult();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    //
    public function add(Device $device): DeviceId
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'deviceId');
        $sql = $this->readNativeQueryFile('insertDevice');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameters([
            'deviceName' => $device->getDeviceName()->toString(),
            'bumonCode' => $device->getDeliveryWorkplace()->getDeliveryWorkplaceCode()->toString(),
            'deviceLabel' => $device->getDeviceLabel()->toString(),
            'programName' => $device->getPacsystemUserId()->toString(),
            'deviceIpAddress' => $device->getDeviceIpAddress()->toString(),
            'deviceLocation' => $device->getDeviceLocation()->toString(),
        ]);

        try {
            return new DeviceId($query->getSingleScalarResult());
        } catch (UniqueConstraintViolationException $e) {
            Log::error($e->getMessage());
            throw new WebAPIException(
                ErrorCodeConst::ERROR_400_DUPLICATE_HOSR_NAME_OR_IP_ADDRESS,
                [],
                WebAPIException::HTTP_STATUS_BAD_REQUEST
            );
        }
    }

    //
    public function update(Device $device): DeviceId
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'deviceId');
        $sql = $this->readNativeQueryFile('updateDevice');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameters([
            'deviceId' => $device->getDeviceId()->toInteger(),
            'deviceName' => $device->getDeviceName()->toString(),
            'bumonCode' => $device->getDeliveryWorkplace()->getDeliveryWorkplaceCode()->toString(),
            'deviceLabel' => $device->getDeviceLabel()->toString(),
            'programName' => $device->getPacsystemUserId()->toString(),
            'deviceIpAddress' => $device->getDeviceIpAddress()->toString(),
            'deviceLocation' => $device->getDeviceLocation()->toString(),
            'findDeviceId' => $device->getDeviceId()->toInteger(),
        ]);

        try {
            return new DeviceId($query->getSingleScalarResult());
        } catch (UniqueConstraintViolationException $e) {
            Log::error($e->getMessage());
            throw new WebAPIException(
                ErrorCodeConst::ERROR_400_DUPLICATE_HOSR_NAME_OR_IP_ADDRESS,
                [],
                WebAPIException::HTTP_STATUS_BAD_REQUEST
            );
        }
    }

    public function detail(DeviceId $deviceId): Device
    {
        $rsm = new ResultSetMapping();

        $rsm->addScalarResult('id', 'delivery_device_id');
        $rsm->addScalarResult('computer_name', 'delivery_device_name');
        $rsm->addScalarResult('label', 'delivery_device_label');
        $rsm->addScalarResult('ip_address', 'delivery_device_ip_address');
        $rsm->addScalarResult('location_memo', 'delivery_device_location');
        $rsm->addScalarResult('pacsystem_user_id', 'pacsystem_user_id');
        $rsm->addScalarResult('delivery_work_place_company_name', 'delivery_workplace_company_name');
        $rsm->addScalarResult('delivery_work_place_name', 'delivery_workplace_name');
        $rsm->addScalarResult('delivery_work_place_code', 'delivery_workplace_code');
        $rsm->addScalarResult('delivery_work_place_code_s', 'delivery_workplace_code_s');
        $rsm->addScalarResult('ht_device_id', 'h_t_device__h_t_device_id');
        $rsm->addScalarResult('ht_device_ip_address', 'h_t_device__h_t_device_ip_address');
        $rsm->addScalarResult('ht_device_location_memo', 'h_t_device__h_t_device_location_memo');
        $rsm->addScalarResult('ht_device_type', 'h_t_device__h_t_device_type');

        $sql = $this->readNativeQueryFile('findDevice');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        $query->setParameters([
            'deviceId' => $deviceId->toInteger()
        ]);
        try {
            return $this->getSingleGroupingResult($query->getResult());
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * ipAddress重複チェック
     * @param DeviceIpAddress $ipAddress
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws BindingResolutionException
     */
    public function isDuplicate(DeviceIpAddress $ipAddress): bool
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('count', 'count');
        $sql = $this->readNativeQueryFile('checkDuplicateIp');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameters([
            'ipAddress' => $ipAddress->getValue()
        ]);
        try {
            return (bool)$query->getSingleScalarResult();
        } catch (NoResultException $e) {
            throw $e;
        }
    }

    /**
     * IpAddress存在確認
     * @param DeviceId $deviceId
     * @param DeviceIpAddress $ipAddress
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws BindingResolutionException
     */
    public function existIpAddress(DeviceId $deviceId, DeviceIpAddress $ipAddress): bool
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('count', 'count');
        $sql = $this->readNativeQueryFile('existHostDeviceIp');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameters([
            'ipAddress' => $ipAddress->getValue(),
            'computerId' => $deviceId->getValue()
        ]);
        try {
            return (bool)$query->getSingleScalarResult();
        } catch (NoResultException $e) {
            throw $e;
        }
    }
}
