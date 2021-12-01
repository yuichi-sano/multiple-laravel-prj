INSERT INTO sample.users
  (user_id, access_id, password, expires_at, created_at, created_user)
VALUES
  ('1', '1000', '$2y$10$2/rAVbqu0ooVJnnV6yTxXuvC7d1YxM36Luwmu5zvdJJM4pmF3cuEG', now() + interval '3 months', now(), 'system')
;

