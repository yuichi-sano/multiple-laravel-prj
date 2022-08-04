INSERT INTO sample.devices( name, work_place_id, user_id, ip_address)
VALUES ( :deviceName
       , :workplaceId
       , :userId
       , :deviceIpAddress
       ) RETURNING id
;
