UPDATE sampleaudit.table_batch_audit
SET status             = '2',
    implementation_date=now(),
    audit_date=now()
WHERE target_table_name = :target_table_name
  AND status = :status
;

