<?php

namespace packages\infrastructure\source\migration\jp;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use packages\domain\model\zipcode\YuseiLargeBusinessYubinBangouList;
use packages\domain\model\zipcode\ZipCodeList;
use packages\domain\model\zipcode\ZipCodeMigrationSourceRepository;
use packages\domain\model\zipcode\ZipCodeSourceDiff;
use packages\domain\model\zipcode\ZipCodeSourceDiffList;

class ZipCodeMigrationDataSource implements ZipCodeMigrationSourceRepository
{
    private Filesystem $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    public function zipPut(ZipCodeList $zipCodeList)
    {
        try {
            $stub = $this->files->get(__DIR__ . '/stubs/zip_migration.stub');
        } catch (FileNotFoundException $e) {
        }
        $sqlList = $this->createZipSqlValues($zipCodeList);
        foreach ($sqlList as $idx => $sql) {
            $migrationFile = str_replace(
                ['DUMMY_INSERT_QUERY_STR'],
                [$sql],
                $stub
            );
            $name = 'zips' . $idx;
            if (Storage::disk('data_migrations')->exists('jp/zips/' . $name . '.sql')) {
                Storage::disk('data_migrations')->delete('jp/old/zips/' . $name . '.sql');
                Storage::disk('data_migrations')->move(
                    'jp/zips/' . $name . '.sql',
                    'jp/old/zips/' . $name . '.sql'
                );
            }
            Storage::disk('data_migrations')->put('jp/zips/' . $name . '.sql', $migrationFile);
        }
    }

    public function yuseiYubinBangouPut(ZipCodeList $zipCodeList)
    {
        try {
            $stub = $this->files->get(__DIR__ . '/stubs/yuseiyubinbangou_migration.stub');
        } catch (FileNotFoundException $e) {
        }
        $sqlList = $this->createYuseiYubinBangouSqlValues($zipCodeList);
        foreach ($sqlList as $idx => $sql) {
            $migrationFile = str_replace(
                ['DUMMY_INSERT_QUERY_STR'],
                [$sql],
                $stub
            );
            $name = 'yuseiyubinbangous' . $idx;
            if (Storage::disk('data_migrations')->exists('jp/yuseiyubinbangous/' . $name . '.sql')) {
                Storage::disk('data_migrations')->delete('jp/old/yuseiyubinbangous/' . $name . '.sql');
                Storage::disk('data_migrations')->move(
                    'jp/yuseiyubinbangous/' . $name . '.sql',
                    'jp/old/yuseiyubinbangous/' . $name . '.sql'
                );
            }
            Storage::disk('data_migrations')->put('jp/yuseiyubinbangous/' . $name . '.sql', $migrationFile);
        }
    }

    public function yuseiLargeBusinessYubinBangouPut(YuseiLargeBusinessYubinBangouList $list)
    {
        try {
            $stub = $this->files->get(__DIR__ . '/stubs/yuseiooguchijigyoushoyubinbangou_migration.stub');
        } catch (FileNotFoundException $e) {
        }
        $sqlList = $this->createYuseiLargeBusinessYubinBangouSqlValues($list);
        foreach ($sqlList as $idx => $sql) {
            $migrationFile = str_replace(
                ['DUMMY_INSERT_QUERY_STR'],
                [$sql],
                $stub
            );
            $name = 'yuseiooguchijigyoushoyubinbangous' . $idx;
            if (
                Storage::disk('data_migrations')->exists(
                    'jp/yuseiooguchijigyoushoyubinbangous/' . $name . '.sql'
                )
            ) {
                Storage::disk('data_migrations')->delete(
                    'jp/old/yuseiooguchijigyoushoyubinbangous/' . $name . '.sql'
                );
                Storage::disk('data_migrations')->move(
                    'jp/yuseiooguchijigyoushoyubinbangous/' . $name . '.sql',
                    'jp/old/yuseiooguchijigyoushoyubinbangous/' . $name . '.sql'
                );
            }
            Storage::disk('data_migrations')->put(
                'jp/yuseiooguchijigyoushoyubinbangous/' . $name . '.sql',
                $migrationFile
            );
        }
    }

    /**
     * @return array
     */
    public function zipDiff(): ZipCodeSourceDiffList
    {
        $target = 'zips';
        $list = Storage::disk('data_migrations')->files('jp/' . $target . '/');
        $result = new ZipCodeSourceDiffList();
        foreach ($list as $item) {
            $itemPath = pathinfo($item);
            $diff = $this->executeDiff($target, $itemPath["filename"]);
            $result = $result->merge($diff);
        }
        return $result;
    }

    /**
     * @return array
     */
    public function yuseiYubinBangouDiff(): ZipCodeSourceDiffList
    {
        $target = 'yuseiyubinbangous';
        $list = Storage::disk('data_migrations')->files('jp/' . $target . '/');
        $result = new ZipCodeSourceDiffList();
        foreach ($list as $item) {
            $itemPath = pathinfo($item);
            $diff = $this->executeDiff($target, $itemPath["filename"]);
            $result = $result->merge($diff);
        }
        return $result;
    }

    /**
     * @return array
     */
    public function yuseiLargeBusinessYubinBangouDiff(): ZipCodeSourceDiffList
    {
        $target = 'yuseiooguchijigyoushoyubinbangous';
        $list = Storage::disk('data_migrations')->files('jp/' . $target . '/');
        $result = new ZipCodeSourceDiffList();
        foreach ($list as $item) {
            $itemPath = pathinfo($item);
            $diff = $this->executeDiff($target, $itemPath["filename"]);
            $result = $result->merge($diff);
        }
        return $result;
    }

    /**
     * insert文のvalue値後から作成
     * @param ZipCodeList $list
     * @return array
     */
    private function createZipSqlValues(ZipCodeList $list): array
    {
        $groupList = array_chunk($list->toArray(), config('app.migration_data_chunk_length'));
        $valuesList = [];
        foreach ($groupList as $list) {
            $values = null;
            $first = true;
            Log::error(count($list));
            foreach ($list as $record) {
                //valueの最初であれば"("のみ
                $value = $first ? "( " : ",( ";
                $value .= "'" . $record->getId()->getValue() . "',";
                $value .= "'" . $record->getZipCode()->getValue() . "',";
                $value .= "'" . $record->getPrefecture() . "',";
                $value .= "'" . $record->getCity() . "',";
                $value .= "'" . $record->getTownArea() . "',";
                $value .= "'" . $record->getPrefectureCode() . "',";
                $value .= "'" . class_basename($this) . "'";
                //value値の終了")"をつける
                $value .= " )\n";
                $first = false;
                $values .= $value;
            }
            $values .= ";";
            $valuesList[] = $values;
        }
        return $valuesList;
    }

    /**
     * insert文のvalue値後から作成
     * @param ZipCodeList $list
     * @return array
     */
    private function createYuseiYubinBangouSqlValues(ZipCodeList $list): array
    {
        $groupList = array_chunk($list->toArray(), config('app.migration_data_chunk_length', 50000));
        $valuesList = [];
        foreach ($groupList as $list) {
            $values = null;
            $first = true;
            foreach ($list as $record) {
                //valueの最初であれば"("のみ
                $value = $first ? "( " : ",( ";
                $value .= "'" . $record->getJis()->getValue() . "',";
                $value .= "'" . substr($record->getZipCode5()->getValue(), 0, -2) . "',";
                $value .= "'" . $record->getZipCode()->getValue() . "',";
                $value .= "'" . $record->getPrefectureKana() . "',";
                $value .= "'" . $record->getCityKana() . "',";
                $value .= "'" . $record->getTownAreaKana() . "',";
                $value .= "'" . $record->getPrefecture() . "',";
                $value .= "'" . $record->getCity() . "',";
                $value .= "'" . $record->getTownArea() . "',";
                $value .= "'" . $record->getIsMultiTownByOnePostCode() . "',";
                $value .= "'" . $record->getIsNeedSmallAreaAddress() . "',";
                $value .= "'" . $record->getIsChoume() . "',";
                $value .= "'" . $record->getIsOneTownByMultiZipCode() . "',";
                $value .= "'" . $record->getUpdated() . "',";
                $value .= "'" . $record->getUpdateReason() . "',";
                $value .= "'" . class_basename($this) . "'";
                //value値の終了")"をつける
                $value .= " )\n";
                $first = false;
                $values .= $value;
            }
            $values .= ";";
            $valuesList[] = $values;
        }
        return $valuesList;
    }

    /**
     * insert文のvalue値後から作成
     * @param YuseiLargeBusinessYubinBangouList $list
     * @return array
     */
    private function createYuseiLargeBusinessYubinBangouSqlValues(YuseiLargeBusinessYubinBangouList $list): array
    {
        $groupList = array_chunk($list->toArray(), config('app.migration_data_chunk_length', 50000));
        $valuesList = [];
        foreach ($groupList as $list) {
            $values = null;
            $first = true;
            foreach ($list as $record) {
                //valueの最初であれば"("のみ
                $value = $first ? "( " : ",( ";
                $value .= "'" . $record->getJis()->getValue() . "',";
                $value .= "'" . $record->getBusinessName() . "',";
                $value .= "'" . $record->getBusinessNameKana() . "',";
                $value .= "'" . $record->getPrefecture() . "',";
                $value .= "'" . $record->getCity() . "',";
                $value .= "'" . $record->getTownArea() . "',";
                $value .= "'" . $record->getAddress() . "',";
                $value .= "'" . $record->getZipCode()->getValue() . "',";
                $value .= "'" . $record->getZipCode5()->getValue() . "',";
                $value .= "'" . $record->getPostalOfficeName() . "',";
                $value .= "'" . $record->getKbn() . "',";
                $value .= "'" . $record->getHasMultipleKbn() . "',";
                $value .= "'" . $record->getFixKbn() . "',";
                $value .= "'" . class_basename($this) . "'";
                //value値の終了")"をつける
                $value .= " )\n";
                $first = false;
                $values .= $value;
            }
            $values .= ";";
            $valuesList[] = $values;
        }
        return $valuesList;
    }

    /**
     * ファイル差分を取得実行
     * @caution Linuxコマンドを実行しますのでWindowsプラットフォームで動作しません
     * @param string $target
     * @return ZipCodeSourceDiffList
     */
    private function executeDiff(string $target, $fileName): ZipCodeSourceDiffList
    {
        $oldPath = data_migrations_path('/jp/old/' . $target . '/' . $fileName . '.sql');
        $newPath = data_migrations_path('/jp/' . $target . '/' . $fileName . '.sql');

        $cmd = 'diff -u  ' . $oldPath . ' ' . $newPath;
        exec($cmd, $diffOut, $result);
        $diffStr = implode("\n", $diffOut);
        $diffArray = preg_split('/(.*\R)/', $diffStr, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $resultDiffResult = array();
        $key = 0;
        $seq = 0;
        foreach ($diffArray as $diff) {
            if (str_starts_with($diff, '---') | str_starts_with($diff, '+++')) {
                continue;
            }
            if (str_starts_with($diff, '-')) {
                $key = $seq === 1 ? $key + 1 : $key;
                $resultDiffResult[$key]['old'] = $diff;
                $seq = 1;
            } elseif (str_starts_with($diff, '+')) {
                $key = $seq === 0 ? $key + 1 : $key;
                $resultDiffResult[$key]['new'] = $diff;
                $seq = 0;
                $key = $key + 1;
            } else {
                continue;
            }
        }
        return ZipCodeSourceDiffList::create($resultDiffResult);
    }
}
