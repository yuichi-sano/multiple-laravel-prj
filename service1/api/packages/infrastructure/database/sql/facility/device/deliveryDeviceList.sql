SELECT computers.id
     , computers.computer_name
     , computers.facility_code
     , computers.label
     , computers.program_name
     , computers.ip_address
     , computers.location_memo
     , computers.program_name          as _user_id
     , job_work_places.job_work_place_id
     , delivery_work_places.meishou1   as delivery_work_place_company_name
     , delivery_work_places.meishou2   as delivery_work_place_name
     , delivery_work_places.facility_code as delivery_work_place_code
     , delivery_work_places.meishou3   as delivery_work_place_code_s
FROM sample.devices
         JOIN delivery_work_places ON delivery_work_places.facility_code = computers.facility_code
         JOIN job_work_places ON job_work_places.job_work_place_id = delivery_work_places.job_work_place_id
WHERE 1 = 1 @if(!$criteria->workplaceCode->isEmpty())
  AND  delivery_work_places.facility_code='{{$criteria->workplaceCode->getValue()}}'
@endif
--LIMIT
--{{$criteria->pageable->getPerPage()->getValue()}}
--OFFSET
--{{$criteria->pageable->getOffset()->getValue()}}
;
