SELECT ooguchijigyoushotou_kobetsu_bangou as zipcode, *
FROM sample.yuseiooguchijigyoushoyubinbangous_audit
WHERE 1 = 1
ORDER BY audit_date DESC LIMIT 5;
