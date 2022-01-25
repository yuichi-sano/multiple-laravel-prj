<?php
declare(strict_types=1);

namespace packages\domain\model\zipcode\split_townArea;
use packages\domain\model\zipcode\ZipCodeConstants;

class TownAreaAnalyzer
{
    /**
     * @param  string $townArea 町域名称
     * @return bool             判定結果
     */
    static function hasSub($townArea): bool
    {
        return (bool)preg_match(
             ZipCodeConstants::REGEX_INSIDE_PARENTHESES
            ,$townArea
        );
    }

    /**
     * @param  string $townArea 町域名称カナ
     * @return bool             判定結果
     */
    static function hasSubKana($townAreaKana): bool
    {
        return (bool)preg_match(
             ZipCodeConstants::REGEX_INSIDE_PARENTHESES_KANA
            ,$townAreaKana
        );
    }

    /**
     * @param  string $townArea 町域名称カナ
     * @return bool             判定結果
     */
    static function hasMainKana($townAreaKana): bool
    {
        return (bool)preg_match(
             ZipCodeConstants::REGEX_BEFORE_PARENTHESES_KANA
            ,$townAreaKana
        );
    }

    /**
     * 町域カナの分割要否を判定する
     * @param  string  $townAreaKana 町域カナ情報
     * @return bool                  分割要否
     */
    static function needSplitKana(string $townAreaKana): bool
    {
        return (bool)preg_match(
             ZipCodeConstants::REGEX_SEPARATE_TOWNAREA_KANA
            ,$townAreaKana
        );
    }

    /**
     * 渡された引数に半角数字が使用されているか判定する
     * @param  string $str 判定する文字列
     * @return bool        半角数字の有無
     */
    static function hasHalfSizeNum(string $str): bool
    {
        return (bool)preg_match(
             ZipCodeConstants::REGEX_NUMBER_HALF
            ,$str
        );
    }

    /**
     * 渡された引数の最後の数字の後に文字列が存在するか判定する
     * 主に丁目、や区など、数字の後に住所区分単位が存在有無の判定に使用
     * @param  string $townAreaKana 判定する文字列
     * @return bool                 文字列の有無
     */
    static function hasAfterSerialTownAreaKana(string $townAreaKana): bool
    {
        return (bool)preg_match(
             ZipCodeConstants::REGEX_AFTER_SERIAL_TOWNAREA_KANA
            ,$townAreaKana
        );

    }}
