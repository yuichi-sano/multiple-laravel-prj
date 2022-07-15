<?php

declare(strict_types=1);

namespace packages\domain\model\zipcode\helper;

class ZipCodeConstants
{
    /* 町域名称（カナ）の処理用正規表現 */
    public const REGEX_UNCLOSED_PARENTHESES = "/（.*[^）]$/u";
    public const REGEX_UNCLOSED_PARENTHESES_KANA = "/\\(.*[^\\)]$/u";
    public const REGEX_CLOSED_PARENTHESES = "/.*.）$/u";
    public const REGEX_CLOSED_PARENTHESES_KANA = "/.*.\\)$/u";
    public const REGEX_FLOOR = "/（([０-９]+階)）/u";
    public const REGEX_FLOOR_KANA = "/\\(([0-9]+ｶｲ)\\)/u";
    public const REGEX_JIWARI = "/^([^０-９第（]+)|[第]*[０-９]+地割.*/u";
    public const REGEX_JIWARI_KANA = "/^([^0-9\\(]+)|(ﾀﾞｲ)*[0-9(]*ﾁﾜﾘ.*/u";
    public const REGEX_JIWARI_EXCEPT_TOWN = "/^(?=[^０-９第（]+)|[第]*[０-９]+地割.*/u";
    public const REGEX_JIWARI_KANA_EXCEPT_TOWN = "/^(?=[^0-9\\(]+)|(ﾀﾞｲ)*[0-9(]*ﾁﾜﾘ.*/u";
    public const REGEX_PARENTHESES = "/（(.*)）/u";
    public const REGEX_PARENTHESES_KANA = "/\\((.*)\\)/u";
    public const REGEX_BEFORE_PARENTHESES = "/(.*)(?=（)/u";
    public const REGEX_BEFORE_PARENTHESES_KANA = "/[^\(]+(?=\()/u";
    public const REGEX_INSIDE_PARENTHESES = "/(?<=（).*(?=）)/u";
    public const REGEX_INSIDE_PARENTHESES_KANA = "/(?<=\().*(?=\))/u";
    public const REGEX_SEPARATE_TOWNAREA = "/.*、.*/u";
    public const REGEX_SEPARATE_TOWNAREA_KANA = "/.*､.*/u";
    public const REGEX_SERIAL_TOWNAREA = "/.*[～〜].*/u";
    public const REGEX_BEFORE_SERIAL_TOWNAREA = "/(.*)(?=[～〜])/u";
    public const REGEX_BEFORE_SERIAL_TOWNAREA_KANA = "/(.*)(?=-)/u";
    public const REGEX_AFTER_SERIAL_TOWNAREA = "/(?<=[～〜]).*/u";
    public const REGEX_AFTER_SERIAL_TOWNAREA_KANA = "/(?<=-)[^-]+/u";
    public const REGEX_SERIAL_BANCHIGOU = "/(?<=[～〜])[０-９]+[－−][０-９]+/u";
    public const REGEX_USE_IN_PARENTHESES = "/[０-９]+区/u";
    public const REGEX_USE_IN_PARENTHESES_KANA = "/[0-9]+ｸ/u";
    public const REGEX_BULLETS = "/.*・.*/u";
    public const REGEX_KEYBRACKETS = "/.*「.*」.*/u";
    public const REGEX_UNITS = "/地割|丁目|番地|区|線|条/u";
    public const REGEX_NUMBER = "/[０-９]+/u";
    public const REGEX_NUMBER_HALF = "/[0-9]+/u";
    public const REGEX_NOT_NUMBER = "/[^０-９]+/u";
    public const REGEX_NOT_NUMBER_HALF = "/[^0-9]+/u";
    public const REGEX_DEPRECATED_PATTERN_COLLECTION = array("/抜海村バッカイ/u");
    public const REGEX_NOT_SERIALEND_NUMBER =
        "/[０-９]+(((番地)?[～〜][、）])+|((番地)?[～〜]$))/u";
    public const REGEX_IGNORE =
        "/以下に掲載がない場合|(市|町|村)の次に番地がくる場合|(市|町|村)一円/u";
    public const REGEX_IGNORE_IN_PARENTHESES =
        "/[０-９]|^その他$|^丁目$|^番地$|^地階・階層不明$|[０-９]*地割"
        + "|成田国際空港内|次のビルを除く|^全域$/u";
    /* ken_all.csvの属性に紐付いたインデックス */
    public const IDX_JIS = 0;
    public const IDX_ZIPCODE5 = 1;
    public const IDX_ZIPCODE = 2;
    public const IDX_PREFECTURE_KANA = 3;
    public const IDX_CITY_KANA = 4;
    public const IDX_TOWNAREA_KANA = 5;
    public const IDX_PREFECTURE = 6;
    public const IDX_CITY = 7;
    public const IDX_TOWNAREA = 8;
    public const IDX_IS_ONETOWN_BY_MULTIZIPCODE = 9;
    public const IDX_IS_NEEDS_SMALLAREAADDRESS = 10;
    public const IDX_IS_CHOUME = 11;
    public const IDX_IS_MULTITOWN_BY_ONEPOSTCODE = 12;
    public const IDX_UPDATED = 13;
    public const IDX_UPDATEREASON = 14;
}
