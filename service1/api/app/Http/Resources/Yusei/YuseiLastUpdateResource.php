<?php

namespace App\Http\Resources\Yusei;

use App\Http\Resources\Basic\AbstractJsonResource;
use App\Http\Resources\Definition\Yusei\UpdateInfoResultDefinition;
use packages\domain\model\batch\MigrationBatchAuditList;
use packages\domain\model\zipcode\YuseiYubinBangouList;


class YuseiLastUpdateResource extends AbstractJsonResource
{
    public static function buildResult(
        MigrationBatchAuditList $latestMigration,
        YuseiYubinBangouList $latestUpdate
    ): YuseiLastUpdateResource {
        $definition = new UpdateInfoResultDefinition();

        foreach ($latestMigration as $migration) {
            $definition->setBulkUser($migration->getUserId()->getValue());
            $definition->setBulkUpdateDate($migration->getApplyDate()->format());
        }
        if (!$latestUpdate->isEmpty()) {
            $update = $latestUpdate->first();
            $definition->setSikumei($update->getCity());
            $definition->setKenmei($update->getPrefecture());
            $definition->setSikuCode($update->getJis()->getValue());
            $definition->setKenCode($update->getJis()->getPrefCode()->getValue());
            $definition->setAddDate($update->getAuditDate()->format());
            $definition->setUser($update->getUserId()->getValue());
            $definition->setZipCode($update->getZipCode()->getValue());
        }

        return new YuseiLastUpdateResource($definition);
    }
}
