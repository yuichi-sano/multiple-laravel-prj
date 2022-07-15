SELECT user_id
FROM sample.users mpau
         JOIN sample.user_refresh_tokens token
              ON mpau.user_id = token._user_id
WHERE token.refresh_token = :refreshToken
  AND token.expires_at > now();
