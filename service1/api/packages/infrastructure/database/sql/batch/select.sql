SELECT *
FROM sampleaudit.table_batch_audit @isset($criteria)
WHERE
    @isset($criteria-
    >targetTableName)
    target_table_name='{{$criteria->targetTableName}}'
    @endisset
    @isset($criteria-
    >targetTableName)
  AND status='{{$criteria->status->getValue()}}'
    @endisset

    @endisset
;

