SELECT count(1)
FROM (SELECT 1
      FROM sample.devices
               JOIN delivery_work_places
                    ON delivery_work_places.facility_code = computers.facility_code
               JOIN job_work_places ON job_work_places.job_work_place_id =
                                           delivery_work_places.job_work_place_id
               LEFT JOIN ht_devices mhd on computers.id = mhd.computer_id
      WHERE 1 = 1 @if(!$criteria->workplaceCode->isEmpty())
                           AND  computers.facility_code='{{$criteria->workplaceCode->getValue()}}'
                             @endif
      GROUP BY computers.id) A
;
