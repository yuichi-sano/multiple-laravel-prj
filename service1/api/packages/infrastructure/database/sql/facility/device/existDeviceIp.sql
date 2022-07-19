SELECT count(1)
FROM computers
WHERE 1 = 1
  AND ip_address = :ipAddress
  AND id = :computerId
;