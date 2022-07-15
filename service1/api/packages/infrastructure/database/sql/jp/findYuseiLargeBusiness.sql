SELECT ooguchijigyoushotou_kobetsu_bangou as zipcode,
       *
FROM sample.yuseiooguchijigyoushoyubinbangous
WHERE 1 = 1
  AND ooguchijigyoushotou_kobetsu_bangou = :zipCode;
