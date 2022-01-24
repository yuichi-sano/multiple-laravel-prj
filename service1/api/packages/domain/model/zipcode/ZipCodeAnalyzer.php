<?php
declare(strict_types=1);

namespace packages\domain\model\zipcode;
use packages\domain\model\zipcode\ZipCodeConstants;

class ZipCodeAnalyzer
{
    /**
     * @param  string $townArea 町域名称カナ
     * @return bool             判定結果
     */
    static function hasKanaSub($townAreaKana): bool
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
    static function hasKanaMain($townAreaKana): bool
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
}
