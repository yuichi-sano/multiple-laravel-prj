SELECT count(1)
FROM sample.devices
WHERE A.ip_address = :ipAddress
;
