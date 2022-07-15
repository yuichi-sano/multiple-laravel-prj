SELECT zips.id
     , zips.zipcode
     , zips.ken_code
     , yuseiyubinbangous.*
FROM sample.zips
         JOIN sample.yuseiyubinbangous
              ON zips.zipcode = yuseiyubinbangous.yubinbangou
WHERE 1 = 1
  AND id = :id;
