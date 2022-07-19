INSERT INTO sample.table_batch_audit
(target_table_name,
 record_cnt,
 before_record_cnt,
 diff_cnt,
 status,
 apply_date,
 user_id,
 create_date)
VALUES (:target_table_name,
        :record_cnt,
        :before_record_cnt,
        :diff_cnt,
        :status,
        :apply_date,
        :user_id,
        now());

