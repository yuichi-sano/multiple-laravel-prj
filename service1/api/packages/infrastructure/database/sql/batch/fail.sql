UPDATE sample.table_batch_audit
SET status = '4'
WHERE target_table_name = :target_table_name
  AND status = :status
;

