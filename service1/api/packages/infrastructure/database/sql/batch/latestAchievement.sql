SELECT *
FROM sampleaudit.table_batch_audit
WHERE target_table_name = :target_table_name
  AND status in ('2', '4')
ORDER BY implementation_date DESC LIMIT  1
;

