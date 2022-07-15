SELECT user_id
     , '' as name
     , password
     , expires_at
     , readonly_flag
     , zip
     , pref_code
     , address
FROM sample.users
         JOIN sample.user_addresses USING (user_id)

