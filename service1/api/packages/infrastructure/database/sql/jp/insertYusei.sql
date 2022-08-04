INSERT INTO yuseiyubinbangous
(jis, kyu_yubinbangou, yubinbangou, todoufuken_kana, shikuchouson_kana, chouiki_kana, todoufuken_kanji,
 shikuchouson_kanji, chouiki_kanji,
 fukusu_yubinbangou_kbn, koazagoto_banchi_kbn, choumu_yuchouiki_kbn, fukusu_chouiki_kbn, koushin_hyoji_kbn,
 henkou_riyu_kbn, program_name)
VALUES (:jis, :zipCodeOld, :zipCode, :prefNameKana, :cityNameKana, :townNameKana, :prefName, :cityName, :townName,
        :isOneTownByMultiZipCode, :isNeedSmallAreaAddress, :isChoume, :isMultiTownByOnePostCode, :updated,
        :updateReason, :UserId);


