SELECT devices.id
     , devices.name
     , devices.ip_address
     , devices.user_id
     , work_places.work_place_id
FROM sample.devices
         JOIN sample.work_places ON work_places.work_place_id = devices.work_place_id
WHERE 1 = 1
AND devices.id = :deviceId