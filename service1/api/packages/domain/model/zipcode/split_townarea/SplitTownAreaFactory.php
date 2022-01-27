<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode\split_townarea;

use App\Exceptions\WebAPIException;
use packages\domain\model\zipcode\split_townArea\TownAreaAnalyzer;
use packages\domain\model\zipcode\ZipCodeConstants;

class SplitTownAreaFactory {

    /**
     * 町域（カナ）情報がどの分割パターンか判定して適切な分割クラスを提供
     * @param  string $townArea 町域名称
     * @return int              分割パターン
     * @throws WebAPIException  どの分割パターンにも該当しないデータの検出
     */
    public function production(string $townArea): SplitTownArea
    {
        // 主・従属パターン
        if($this->isMainSub($townArea)) {
            return new SplitMainSub;
        }
        // 複数の主パターン
        if($this->isMultiMain($townArea)) {
            return new SplitMultiMain;
        }
        // 連番の主パターン
        if($this->isSerialMain($townArea)) {
            return new SplitSerialMain;
        }
        // 主と複数の住所単位パターン
        if($this->isMainMultiUnit($townArea)) {
            return new SplitMainMultiUnit;
        }
        // 主と連続した住所単位パターン
        if($this->isMainSerialUnit($townArea)) {
            return new SplitMainSerialUnit;
        }
        // 主・従属と連続した住所単位パターン
        if($this->isMainSubSerialUnit($townArea)) {
            return new SplitMainSubSerialUnit;
        }
        // 連続の主と・単一の従属町域パターン
        if($this->isSerialMainSub($townArea)) {
            return new SplitSerialMainSub;
        }

        // 該当しないものは、データが新しく追加になったか変更されたものを想定
        throw new WebAPIException('無効なデータの検出');
    }

    /**
     * 町域が主・従属パターンか否かを判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isMainSub(string $townArea): bool
    {
        // 主・従属パターンは、主と複数の住所単位パターンを包括している
        // -> 主と複数の住所単位パターンに該当しないものを主・従属パターンとして処理
        // 〜を使用しているものはこの分割パターンに該当しないが、
        // 例外的に〜と、`・`もしくは`「」`を同時で使用する場合はここで処理する
        // -> 例：西瑞江（４丁目１～２番・１０～２７番、５丁目）
        // -> 例：折茂（今熊「２１３〜２３４、（中略）１５０４を除く」、大原、（以下略）
        return   TownAreaAnalyzer::hasMain($townArea)
              && TownAreaAnalyzer::hasParent($townArea)
              && TownAreaAnalyzer::hasSeparator($townArea)
              && (
                       !TownAreaAnalyzer::hasSerial($townArea)
                     || TownAreaAnalyzer::hasBullets($townArea)
                     || TownAreaAnalyzer::hasKeyBrackets($townArea)
                     || TownAreaAnalyzer::hasNotSerialEndNumber($townArea)
                 )
              && !$this->isMainMultiUnit($townArea);
    }

    /**
     * 町域が2つの主パターンか否かを判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isMultiMain(string $townArea): bool
    {
        // 一時変数にしないとエラーになる
        $townAreaNum = count(explode('、', $townArea));
        return !TownAreaAnalyzer::hasSub($townArea)
            && !TownAreaAnalyzer::hasParent($townArea)
            && $townAreaNum > 1;
    }

    /**
     * 町域が連続の主パターンか否かを判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isSerialMain(string $townArea): bool
    {
        return !TownAreaAnalyzer::hasSub($townArea)
            && !TownAreaAnalyzer::hasParent($townArea)
            &&  TownAreaAnalyzer::hasSerial($townArea);
    }

    /**
     * 町域が主と複数の住所単位パターンか否か判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isMainMultiUnit(string $townArea): bool
    {
        if(
               TownAreaAnalyzer::hasMain($townArea)
            && TownAreaAnalyzer::hasSeparator($townArea)
        ) {
            preg_match(
                 ZipCodeConstants::REGEX_INSIDE_PARENTHESES
                ,$townArea
                ,$units
            );
            $units       = explode('、', $units[0]);
            $unitNum     = count(explode('、', $townArea));
            $hasUnitName = (bool)preg_match(
                ZipCodeConstants::REGEX_UNITS,
                $units[count($units)-1]
            );
            array_pop($units);

            //カッコ内の、最後を除いた要素が数字以外ならこのパターンに該当しない
            $isRemainNumOnly = true;
            foreach($units as $remainElement) {
                $isRemainNumOnly = !TownAreaAnalyzer::hasNotNumber($remainElement);
            }

            return $unitNum > 1
                && $hasUnitName
                && $isRemainNumOnly;
        } else {
            return false;
        }
    }

    /**
     * 町域が主と連続した住所単位パターンか否か判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isMainSerialUnit(string $townArea): bool
    {
        if(
                TownAreaAnalyzer::hasMain($townArea)
            && !TownAreaAnalyzer::hasSeparator($townArea)
        ) {

            preg_match(
                ZipCodeConstants::REGEX_BEFORE_PARENTHESES
               ,$townArea
               ,$mainTownAreas
            );
            preg_match(
                ZipCodeConstants::REGEX_PARENTHESES
               ,$townArea
               ,$subTownAreas
            );

            return !TownAreaAnalyzer::hasSerial($mainTownAreas[0])
                 && TownAreaAnalyzer::hasSerial($subTownAreas[0]);
        }
        return false;
    }

    /**
     * 町域が主・従属と連続した住所単位パターンか否か判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isMainSubSerialUnit(string $townArea): bool
    {
        return  TownAreaAnalyzer::hasMain($townArea)
            &&  TownAreaAnalyzer::hasSeparator($townArea)
            &&  TownAreaAnalyzer::hasSerial($townArea)
            && !TownAreaAnalyzer::hasNotSerialEndNumber($townArea);
    }

    /**
     * 連続の主と・単一の従属町域パターンか判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isSerialMainSub(string $townArea): bool
    {
        if(
               TownAreaAnalyzer::hasMain($townArea)
            && TownAreaAnalyzer::hasParent($townArea)
        ) {
            preg_match(
                ZipCodeConstants::REGEX_BEFORE_PARENTHESES
                ,$townArea
                ,$mainTownAreas
            );
            preg_match(
                 ZipCodeConstants::REGEX_PARENTHESES
                ,$townArea
                ,$subTownAreas
            );

            return  TownAreaAnalyzer::hasSerial($mainTownAreas[0])
                && !TownAreaAnalyzer::hasSeparator($subTownAreas[0]);
        }
        return false;
    }
}
