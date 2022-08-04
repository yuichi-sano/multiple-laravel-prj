SELECT mpau.user_id
FROM sample.users mpau
         JOIN sample.user_refresh_tokens token
              ON mpau.user_id = token.user_id
WHERE token.refresh_token = :refreshToken
  AND token.expires_at > now();
