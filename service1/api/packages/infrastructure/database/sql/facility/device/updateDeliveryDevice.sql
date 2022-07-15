UPDATE sample.devices
SET id            = :deviceId
  , computer_name = :deviceName
  , facility_code    = :facilityCode
  , label         = :deviceLabel
  , program_name  = :programName
  , ip_address    = :deviceIpAddress
  , location_memo = :deviceLocation
WHERE id = :findDeviceId
;
