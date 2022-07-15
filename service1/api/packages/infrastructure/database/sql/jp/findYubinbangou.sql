SELECT yubinbangou as zipcode,
       *
FROM sample.yuseiyubinbangous
WHERE 1 = 1
  AND yubinbangou = :zipCode;
