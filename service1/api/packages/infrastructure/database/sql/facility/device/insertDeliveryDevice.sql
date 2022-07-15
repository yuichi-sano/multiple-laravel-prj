INSERT INTO sample.devices( computer_name
                                    , facility_code
                                    , label
                                    , program_name
                                    , ip_address
                                    , location_memo)
VALUES ( :deviceName
       , :facilityCode
       , :deviceLabel
       , :programName
       , :deviceIpAddress
       , :deviceLocation) RETURNING id
;
