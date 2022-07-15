DELETE
FROM sample.yuseiyubinbangous
WHERE yubinbangou = :zipCode
  AND jis = :jis
  AND todoufuken_kanji = :prefName
  AND shikuchouson_kanji = :cityName
  AND chouiki_kanji = :townName
;
