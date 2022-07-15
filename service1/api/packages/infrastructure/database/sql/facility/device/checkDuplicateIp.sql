SELECT count(1)
FROM (SELECT ip_address
      FROM computers
      UNION
      SELECT ip_address
      FROM ht_devices) AS A
WHERE A.ip_address = :ipAddress
;
