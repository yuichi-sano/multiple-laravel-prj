SELECT count(1)
FROM sample.devices
JOIN sample.work_places ON work_places.work_place_id =devices.work_place_id
WHERE 1 = 1
@if(!$criteria->workplaceId->isEmpty())
AND  devices.work_place_Id='{{$criteria->workplaceId->getValue()}}'
@endif
;
