INSERT INTO sample.users
  (user_id, access_id, password, expires_at, created_at, created_user)
VALUES
  ('1', '1000', '$2y$10$5mS/fneIqTQ38E0hZjbcB.kBHWMYcUBt49WLJjbaAU.49b76TvKyG', now() + interval '3 months', now(), 'system')
;
INSERT INTO sample.grants
(grant_id, name, start_date, end_date, created_at, created_user)
VALUES
    ('1', '権限1', now(), now() + interval '3 months', now(), 'system'),
    ('2', '権限2', now(), now() + interval '3 months', now(), 'system')
;

INSERT INTO sample.user_grants
(user_id, grant_id, created_at, created_user)
VALUES
    ('1', '1',  now(), 'system'),
    ('1', '2',  now(), 'system')
;

INSERT INTO sample.user_addresses
(user_id, zip, pref_code, address)
VALUES
    ('1', '418-0022',  22, '静岡県富士宮市小泉 1111111'),
    ('1', '418-0022',  22, '静岡県富士宮市小泉 1-1-1 sample 103 号室')
;
