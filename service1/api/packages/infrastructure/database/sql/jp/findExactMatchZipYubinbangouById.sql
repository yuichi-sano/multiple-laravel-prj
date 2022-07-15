SELECT zips.id
     , zips.zipcode
     , zips.ken_code
     , yuseiyubinbangous.*
FROM sample.zips
         JOIN sample.yuseiyubinbangous
              ON zips.zipcode = yuseiyubinbangous.yubinbangou
                  AND zips.sikuika = yuseiyubinbangous.chouiki_kanji
WHERE 1 = 1
  AND id = :id;
