<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode;
class ZipCodeConstants
{
    /* 町域名称（カナ）の処理用正規表現 */
    const REGEX_UNCLOSED_PARENTHESES          = "/（.*[^）]$/u";
    const REGEX_UNCLOSED_PARENTHESES_KANA     = "/\\(.*[^\\)]$/u";
    const REGEX_CLOSED_PARENTHESES            = "/.*.）$/u";
    const REGEX_CLOSED_PARENTHESES_KANA       = "/.*.\\)$/u";
    const REGEX_FLOOR                         = "/（([０-９]+階)）/u";
    const REGEX_FLOOR_KANA                    = "/\\(([0-9]+ｶｲ)\\)/u";
    const REGEX_JIWARI                        = "/^([^０-９第（]+)|[第]*[０-９]+地割.*/u";
    const REGEX_JIWARI_KANA                   = "/^([^0-9\\(]+)|(ﾀﾞｲ)*[0-9(]*ﾁﾜﾘ.*/u";
    const REGEX_JIWARI_EXCEPT_TOWN            = "/^(?=[^０-９第（]+)|[第]*[０-９]+地割.*/u";
    const REGEX_JIWARI_KANA_EXCEPT_TOWN       = "/^(?=[^0-9\\(]+)|(ﾀﾞｲ)*[0-9(]*ﾁﾜﾘ.*/u";
    const REGEX_PARENTHESES                   = "/（(.*)）/u";
    const REGEX_PARENTHESES_KANA              = "/\\((.*)\\)/u";
    const REGEX_BEFORE_PARENTHESES            = "/(.*)(?=（)/u";
    const REGEX_BEFORE_PARENTHESES_KANA       = "/[^\(]+(?=\()/u";
    const REGEX_INSIDE_PARENTHESES            = "/(?<=（).*(?=）)/u";
    const REGEX_INSIDE_PARENTHESES_KANA       = "/(?<=\().*(?=\))/u";
    const REGEX_SEPARATE_TOWNAREA             = "/.*、.*/u";
    const REGEX_SEPARATE_TOWNAREA_KANA        = "/.*､.*/u";
    const REGEX_SERIAL_TOWNAREA               = "/.*[～〜].*/u";
    const REGEX_BEFORE_SERIAL_TOWNAREA        = "/(.*)(?=[～〜])/u";
    const REGEX_BEFORE_SERIAL_TOWNAREA_KANA   = "/(.*)(?=-)/u";
    const REGEX_AFTER_SERIAL_TOWNAREA         = "/(?<=[～〜]).*/u";
    const REGEX_AFTER_SERIAL_TOWNAREA_KANA    = "/(?<=-)[^-]+/u";
    const REGEX_SERIAL_BANCHIGOU              = "/(?<=[～〜])[０-９]+[－−][０-９]+/u";
    const REGEX_USE_IN_PARENTHESES            = "/[０-９]+区/u";
    const REGEX_USE_IN_PARENTHESES_KANA       =  "/[0-9]+ｸ/u";
    const REGEX_BULLETS                       = "/.*・.*/u";
    const REGEX_KEYBRACKETS                   = "/.*「.*」.*/u";
    const REGEX_UNITS                         = "/地割|丁目|番地|区|線|条/u";
    const REGEX_NUMBER                        = "/[０-９]+/u";
    const REGEX_NUMBER_HALF                   = "/[0-9]+/u";
    const REGEX_NOT_NUMBER                    = "/[^０-９]+/u";
    const REGEX_NOT_NUMBER_HALF               = "/[^0-9]+/u";
    const REGEX_DEPRECATED_PATTERN_COLLECTION = array("/抜海村バッカイ/u");
    const REGEX_NOT_SERIALEND_NUMBER          =
        "/[０-９]+(((番地)?[～〜][、）])+|((番地)?[～〜]$))/u";
    const REGEX_IGNORE                        =
        "/以下に掲載がない場合|(市|町|村)の次に番地がくる場合|(市|町|村)一円/u";
    const REGEX_IGNORE_IN_PARENTHESES         =
          "/[０-９]|^その他$|^丁目$|^番地$|^地階・階層不明$|[０-９]*地割"
        + "|成田国際空港内|次のビルを除く|^全域$/u";

    /* ken_all.csvの属性に紐付いたインデックス */
    const IDX_JIS                         = 0;
    const IDX_ZIPCODE5                    = 1;
    const IDX_ZIPCODE                     = 2;
    const IDX_PREFECTURE_KANA             = 3;
    const IDX_CITY_KANA                   = 4;
    const IDX_TOWNAREA_KANA               = 5;
    const IDX_PREFECTURE                  = 6;
    const IDX_CITY                        = 7;
    const IDX_TOWNAREA                    = 8;
    const IDX_IS_ONETOWN_BY_MULTIZIPCODE  = 9;
    const IDX_IS_NEEDS_SMALLAREAADDRESS   = 10;
    const IDX_IS_CHOUME                   = 11;
    const IDX_IS_MULTITOWN_BY_ONEPOSTCODE = 12;
    const IDX_UPDATED                     = 13;
    const IDX_UPDATEREASON                = 14;
}
