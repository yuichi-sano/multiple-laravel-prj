SELECT job_work_place_id,
       job_work_place_name,
       facility_code,
       meishou1,
       meishou2,
       Array_to_string(ARRAY_AGG(facility_code_s), ',') facility_code_s
FROM (SELECT mjwp.job_work_place_id
           , mjwp.factory    as job_work_place_name
           , dwp.facility_code
           , dwps.facility_code as facility_code_s
           , dwp.meishou1
           , CASE
                 WHEN dwp.meishou2 = dwps.meishou2 THEN dwp.meishou2
                 ELSE dwps.meishou2
        END                  as meishou2
      FROM sample.work_places mjwp
      WHERE 1 = 1
      ORDER BY job_work_place_id) a
GROUP BY job_work_place_id, job_work_place_name, facility_code, meishou1, meishou2
;