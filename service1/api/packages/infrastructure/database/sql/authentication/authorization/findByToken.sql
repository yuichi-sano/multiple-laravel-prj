SELECT refresh_token,
       user_id as user_id,
       signs_at,
       expires_at
FROM sample.user_refresh_tokens
WHERE sample.user_refresh_tokens.refresh_token = :refreshToken
