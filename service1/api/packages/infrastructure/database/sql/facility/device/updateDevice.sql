UPDATE sample.devices
SET
  , name = :deviceName
  , work_place_id    = :workplaceId
  , user_id  = :userId
  , ip_address    = :deviceIpAddress
WHERE id = :deviceId
;
