SELECT *
FROM sample.table_batch_audit
WHERE target_table_name = :target_table_name
  AND status = '1'
  AND apply_date BETWEEN DATE_TRUNC('MINUTES', now()) AND DATE_TRUNC('MINUTES', now() + interval '1 minute')
;

