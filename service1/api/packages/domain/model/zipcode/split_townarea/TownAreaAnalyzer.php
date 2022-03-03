<?php
declare(strict_types=1);

namespace packages\domain\model\zipcode\split_townArea;
use packages\domain\model\zipcode\ZipCodeConstants;

class TownAreaAnalyzer
{

    /**
     * 渡された引数に主の町域が存在するか判定する
     * 主に丁目、や区など、数字の後に住所区分単位が存在有無の判定に使用
     * @param  string $townAreaKana 判定する文字列
     * @return bool                 文字列の有無
     */
    static function hasMain(string $townArea): bool
    {
        return (bool)preg_match(
             ZipCodeConstants::REGEX_BEFORE_PARENTHESES
            ,$townArea
        );
    }

    /**
     * 渡された引数に主の町域が存在するか判定する
     * @param  string $townAreaKana 町域名称カナ
     * @return bool                 判定結果
     */
    static function hasMainKana($townAreaKana): bool
    {
        return (bool)preg_match(
             ZipCodeConstants::REGEX_BEFORE_PARENTHESES_KANA
            ,$townAreaKana
        );
    }

    /**
     * 渡された引数に従属する町域が存在するか判定する
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
     * 渡された引数に従属する町域が存在するか判定する
     * @param  string $townAreaKana 町域名称カナ
     * @return bool                 判定結果
     */
    static function hasSubKana($townAreaKana): bool
    {
        return (bool)preg_match(
             ZipCodeConstants::REGEX_INSIDE_PARENTHESES_KANA
            ,$townAreaKana
        );
    }

    /**
     * 渡された引数に連番を表す記号（〜）が存在するか判定する
     * @param  string $townArea 町域名称
     * @return bool             判定結果
     */
    static function hasSerial($townArea): bool
    {
        return (bool)preg_match(
             ZipCodeConstants::REGEX_SERIAL_TOWNAREA
            ,$townArea
        );
    }

    /**
     * 渡された引数に番地と号が存在するか判定する
     * @param  string $townArea     町域名称
     * @return bool                 判定結果
     */
    static function hasSerialBanchiGou($townArea): bool
    {
        return (bool)preg_match(
             ZipCodeConstants::REGEX_SERIAL_BANCHIGOU
            ,$townArea
        );
    }

    /**
     * 渡された引数に半角カナの区切り文字(､)が存在するか判定する
     * @param  string  $townAreaKana 町域名称カナ
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

    }

    /**
     * 渡された引数にカッコが存在するか判定する
     * 主に丁目、や区など、数字の後に住所区分単位が存在有無の判定に使用
     * @param  string $townAreaKana 判定する文字列
     * @return bool                 文字列の有無
     */
    static function hasParent(string $townArea): bool
    {
        return (bool)preg_match(
             ZipCodeConstants::REGEX_PARENTHESES
            ,$townArea
        );
    }

    /**
     * 渡された引数に複数の町域が存在するか判定する
     * 主に丁目、や区など、数字の後に住所区分単位が存在有無の判定に使用
     * @param  string $townAreaKana 判定する文字列
     * @return bool                 文字列の有無
     */
    static function hasSeparator(string $townArea): bool
    {
        return (bool)preg_match(
             ZipCodeConstants::REGEX_SEPARATE_TOWNAREA
            ,$townArea
        );
    }

    /**
     * 渡された引数に中黒（・）が存在するか判定する
     * 主に丁目、や区など、数字の後に住所区分単位が存在有無の判定に使用
     * @param  string $townAreaKana 判定する文字列
     * @return bool                 文字列の有無
     */
    static function hasBullets(string $townArea): bool
    {
        return (bool)preg_match(
             ZipCodeConstants::REGEX_BULLETS
            ,$townArea
        );
    }

    /**
     * 渡された引数にカギ括弧が存在するか判定する
     * 主に丁目、や区など、数字の後に住所区分単位が存在有無の判定に使用
     * @param  string $townAreaKana 判定する文字列
     * @return bool                 文字列の有無
     */
    static function hasKeyBrackets(string $townArea): bool
    {
        return (bool)preg_match(
             ZipCodeConstants::REGEX_KEYBRACKETS
            ,$townArea
        );
    }

    /**
     * 渡された引数に連続しているが終点のない町域が存在するか判定する
     * 例 〇〇地割〜
     * 主に丁目、や区など、数字の後に住所区分単位が存在有無の判定に使用
     * @param  string $townAreaKana 判定する文字列
     * @return bool                 文字列の有無
     */
    static function hasNotSerialEndNumber(string $townArea): bool
    {
        return (bool)preg_match(
             ZipCodeConstants::REGEX_NOT_SERIALEND_NUMBER,
            $townArea
        );
    }

    /**
     * 渡された引数に数字が含まれていないかを判定する
     * 例 〇〇地割〜
     * 主に丁目、や区など、数字の後に住所区分単位が存在有無の判定に使用
     * @param  string $townAreaKana 判定する文字列
     * @return bool                 文字列の有無
     */
    static function hasNotNumber(string $townArea): bool
    {
         return (bool)preg_match(
             ZipCodeConstants::REGEX_NOT_NUMBER
            ,$townArea
         );
    }
}

