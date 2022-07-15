UPDATE sample.zips
SET zipcode      = :postalCode
  , kenmei       = :prefName
  , sikumei      = :city
  , sikuika      = :town
  , ken_code     = :prefCode
  , program_name = :UserId
WHERE id = :id
;