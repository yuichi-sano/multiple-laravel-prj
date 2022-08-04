SELECT devices.id
     , devices.name
     , devices.ip_address
     , devices.user_id
     , work_places.work_place_id
FROM sample.devices
JOIN sample.work_places ON work_places.work_place_id = devices.work_place_id
WHERE 1 = 1
@if(!$criteria->workplaceId->isEmpty())
  AND  work_places.work_place_id='{{$criteria->workplaceId->getValue()}}'
@endif
--LIMIT
--{{$criteria->pageable->getPerPage()->getValue()}}
--OFFSET
--{{$criteria->pageable->getOffset()->getValue()}}
;
