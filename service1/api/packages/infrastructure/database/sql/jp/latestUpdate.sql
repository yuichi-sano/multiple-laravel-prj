SELECT yubinbangou as zipcode, *
FROM sample.yuseiyubinbangous_audit
WHERE 1 = 1
ORDER BY audit_date DESC LIMIT 5;
