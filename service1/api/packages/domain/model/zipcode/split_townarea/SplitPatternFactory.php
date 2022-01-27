<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode\split_townarea;
use packages\domain\model\zipcode\ZipCodeConstants;

class SplitPatternFactory {

    /**
     * 町域（カナ）情報がどの分割パターンか判定する
     * @param  string $townArea
     * @return int    分割パターン
     */
    public function analyzeSplitPattern(string $townArea): SplitTownArea
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

        // 該当なし
        abort(500, '無効なデータの検出: ' . $townArea);
    }

    /**
     * 町域が主・従属パターンか否かを判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isMainSub(string $townArea): bool
    {
        $hasMain        = (bool)preg_match(ZipCodeConstants::REGEX_BEFORE_PARENTHESES, $townArea);
        $hasParent      = (bool)preg_match(ZipCodeConstants::REGEX_PARENTHESES, $townArea);
        $hasSeparater   = (bool)preg_match(ZipCodeConstants::REGEX_SEPARATE_TOWNAREA, $townArea);
        $hasSerial      = (bool)preg_match(ZipCodeConstants::REGEX_SERIAL_TOWNAREA, $townArea);
        $hasBullets     = (bool)preg_match(ZipCodeConstants::REGEX_BULLETS, $townArea);
        $hasKeyBrackets = (bool)preg_match(ZipCodeConstants::REGEX_KEYBRACKETS, $townArea);
        $hasNotSerialEndNumber = (bool)preg_match(
             ZipCodeConstants::REGEX_NOT_SERIALEND_NUMBER,
            $townArea
        );

        // 主・従属パターンは、主と複数の住所単位パターンを包括している
        // -> 主と複数の住所単位パターンに該当しないものを主・従属パターンとして処理
        // 〜を使用しているものはこの分割パターンに該当しないが、
        // 例外的に〜と、`・`もしくは`「」`を同時で使用する場合はここで処理する
        // -> 例：西瑞江（４丁目１～２番・１０～２７番、５丁目）
        // -> 例：折茂（今熊「２１３〜２３４、（中略）１５０４を除く」、大原、（以下略）
        return   $hasMain
              && $hasParent
              && $hasSeparater
              && (!$hasSerial ||$hasBullets || $hasKeyBrackets || $hasNotSerialEndNumber)
              && !$this->isMainMultiUnit($townArea);

    }

    /**
     * 町域が2つの主パターンか否かを判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isMultiMain(string $townArea): bool
    {
        $hasSub      = (bool)preg_match(ZipCodeConstants::REGEX_BEFORE_PARENTHESES, $townArea);
        $hasParent   = (bool)preg_match(ZipCodeConstants::REGEX_PARENTHESES, $townArea);
        $townAreaNum = count(explode('、', $townArea));

        return !$hasSub && !$hasParent && $townAreaNum > 1;

    }

    /**
     * 町域が連続の主パターンか否かを判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isSerialMain(string $townArea): bool
    {
        $hasSub    = (bool)preg_match(ZipCodeConstants::REGEX_BEFORE_PARENTHESES, $townArea);
        $hasParent = (bool)preg_match(ZipCodeConstants::REGEX_PARENTHESES, $townArea);
        $hasSerial = (bool)preg_match(ZipCodeConstants::REGEX_SERIAL_TOWNAREA, $townArea);

        return !$hasSub && !$hasParent && $hasSerial;

    }

    /**
     * 町域が主と複数の住所単位パターンか否か判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isMainMultiUnit(string $townArea): bool
    {
        $hasMain      = (bool)preg_match(ZipCodeConstants::REGEX_BEFORE_PARENTHESES, $townArea);
        $hasSeparater = (bool)preg_match(ZipCodeConstants::REGEX_SEPARATE_TOWNAREA, $townArea);

        if($hasMain && $hasSeparater) {

            preg_match(ZipCodeConstants::REGEX_INSIDE_PARENTHESES, $townArea, $units);
            $units       = explode('、', $units[0]);
            $unitNum     = count(explode('、', $townArea));
            $hasUnitName = (bool)preg_match(
                ZipCodeConstants::REGEX_UNITS,
                $units[count($units)-1]
            );

            $isRemainNumOnly = true;
            array_pop($units);
            //カッコ内の、最後を除いた要素が数字以外ならこのパターンに該当しない
            foreach($units as $remainElement) {
                $isRemainNumOnly = !(bool)preg_match(
                    ZipCodeConstants::REGEX_NOT_NUMBER,
                    $remainElement
                );
            }
            return $unitNum > 1 && $hasUnitName && $isRemainNumOnly;
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
        $hasMain      = (bool)preg_match(ZipCodeConstants::REGEX_BEFORE_PARENTHESES, $townArea);
        $hasSeparater = (bool)preg_match(ZipCodeConstants::REGEX_SEPARATE_TOWNAREA, $townArea);

        if($hasMain && !$hasSeparater){

            $isMainSerial = (bool)preg_match(
                ZipCodeConstants::REGEX_SERIAL_TOWNAREA,
                $this->extractRegex(
                    ZipCodeConstants::REGEX_BEFORE_PARENTHESES,
                    $townArea
                )
            );
            $isSubSerial  = (bool)preg_match(
                ZipCodeConstants::REGEX_SERIAL_TOWNAREA,
                $this->extractRegex(
                    ZipCodeConstants::REGEX_PARENTHESES,
                    $townArea
                )
            );

            return !$isMainSerial && $isSubSerial ;

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
        $hasMain               = (bool)preg_match(
            ZipCodeConstants::REGEX_BEFORE_PARENTHESES,
            $townArea
        );
        $hasSeparater          = (bool)preg_match(
            ZipCodeConstants::REGEX_SEPARATE_TOWNAREA,
            $townArea
        );
        $hasSerial             = (bool)preg_match(
            ZipCodeConstants::REGEX_SERIAL_TOWNAREA,
            $townArea
        );
        $hasNotSerialEndNumber = (bool)preg_match(
             ZipCodeConstants::REGEX_NOT_SERIALEND_NUMBER,
            $townArea
        );

        return $hasMain && $hasSeparater && $hasSerial && !$hasNotSerialEndNumber;
    }

    /**
     * 連続の主と・単一の従属町域パターンか判定
     * @param  string $townArea 元データの町域情報
     * @return bool             判定結果
     */
    private function isSerialMainSub(string $townArea): bool
    {
        $hasMain   = (bool)preg_match(ZipCodeConstants::REGEX_BEFORE_PARENTHESES, $townArea);
        $hasParent = (bool)preg_match(ZipCodeConstants::REGEX_PARENTHESES, $townArea);

        if($hasMain && $hasParent) {
            $isMainSerial   =  (bool)preg_match(
                ZipCodeConstants::REGEX_SERIAL_TOWNAREA,
                self::extractRegex(
                    ZipCodeConstants::REGEX_BEFORE_PARENTHESES,
                    $townArea
                )
            );
            $isSubSeparater = (bool)preg_match(
                    ZipCodeConstants::REGEX_SEPARATE_TOWNAREA,
                    $this->extractRegex(
                        ZipCodeConstants::REGEX_PARENTHESES,
                        $townArea
                    )
                );

            return $isMainSerial && !$isSubSeparater;
        }
        return false;
    }

    /**
     * 引数で渡された正規表現にマッチした文字列を抽出
     * @param  string $regex 正規表現
     * @param  string $str   抽出対象の文字列
     * @return string        抽出したメインの文字列
     */
    private static function extractRegex(string $regex, string $str): string
    {
        if((bool)preg_match($regex, $str)){
            preg_match($regex, $str, $matchedArray);
            return $matchedArray[0];
        } else {
            return '';
        }
    }

}
