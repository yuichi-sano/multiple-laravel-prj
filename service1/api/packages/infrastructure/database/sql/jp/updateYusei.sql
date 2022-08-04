UPDATE sample.yuseiyubinbangous
SET jis                = :jis
  , kyu_yubinbangou    = :zipCodeOld
  , yubinbangou        = :zipCode
  , todoufuken_kana    = :prefNameKana
  , shikuchouson_kana  = :cityNameKana
  , chouiki_kana       = :townNameKana
  , todoufuken_kanji   = :prefName
  , shikuchouson_kanji = :cityName
  , chouiki_kanji      = :townName
  , program_name       = :UserId
WHERE yubinbangou = :lastUpdateZipCode
  AND jis = :lastUpdateJis
  AND todoufuken_kanji = :lastUpdatePrefName
  AND shikuchouson_kanji = :lastUpdateCityName
  AND chouiki_kanji = :lastUpdateTownName
;