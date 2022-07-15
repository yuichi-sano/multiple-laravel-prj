INSERT INTO sample.user_refresh_tokens (refresh_token,
                                                      user_id,
                                                      signs_at,
                                                      expires_at)
VALUES (:refreshToken,
        :UserId,
        :signsAt,
        :expiresAt) ON CONFLICT
ON CONSTRAINT user_refresh_tokens_pkey
    DO
UPDATE SET
    expires_at = :expiresAt
WHERE
    sample.user_refresh_tokens.refresh_token = :refreshToken
