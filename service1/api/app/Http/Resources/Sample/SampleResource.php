<?php

namespace App\Http\Resources\Sample;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Sample\SampleResultDefinition;
use App\Http\Resources\Definition\Sample\SampleResultDefinitionUser;
use App\Http\Resources\Definition\Sample\SampleResultDefinitionUserAddressList;
use packages\domain\model\batch\MigrationBatchAudit;
use packages\domain\model\user\User;


class SampleResource extends AbstractJsonResource
{
    public static function buildResult(MigrationBatchAudit $user): SampleResource
    {
        $definition = new SampleResultDefinition();

        $definitionUser = new SampleResultDefinitionUser();
        $definitionUser->setUserId($user->getStatus()->toInteger());
        $definitionUser->setName($user->getTargetTableName());
        //$definitionAddresses = new SampleResultDefinitionUserAddressList();
        //$definitionAddresses->setAddress($user->getAddresses()->first()->getAddress());

        //$definitionUser->addAddressList($definitionAddresses);
        $definition->setUser($definitionUser);
        return new SampleResource($definition);
    }
}
