INSERT INTO sample.users
  (user_id, access_id, password, expires_at, created_at, created_user)
VALUES
  (1, '1000', '$2y$10$5mS/fneIqTQ38E0hZjbcB.kBHWMYcUBt49WLJjbaAU.49b76TvKyG', now() + interval '3 months', now(), 'system'),--weak-password
  (2, '2000', '$2y$10$5mS/fneIqTQ38E0hZjbcB.kBHWMYcUBt49WLJjbaAU.49b76TvKyG', now() + interval '3 months', now(), 'system'),--weak-password
  (3, '3000', '$2y$10$5mS/fneIqTQ38E0hZjbcB.kBHWMYcUBt49WLJjbaAU.49b76TvKyG', now() + interval '3 months', now(), 'system'),--weak-password
  (4, '4000', '$2y$10$5mS/fneIqTQ38E0hZjbcB.kBHWMYcUBt49WLJjbaAU.49b76TvKyG', now() + interval '3 months', now(), 'system')--weak-password
;
INSERT INTO sample.user_profiles
(user_id, name, tel, mail, created_user)
VALUES
    (1,'test1','11111111111','dummy1@dummy.co.jp','system'),
    (2,'test2','22222222222','dummy2@dummy.co.jp','system'),
    (3,'test3','33333333333','dummy3@dummy.co.jp','system'),
    (4,'test4','44444444444','dummy4@dummy.co.jp','system')
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
    (1, 1,  now(), 'system'),
    (1, 2,  now(), 'system'),
    (3, 1,  now(), 'system'),
    (4, 1,  now(), 'system')
;

INSERT INTO sample.user_addresses
(user_id, zip, pref_code, address)
VALUES
    (1, '418-0022',  22, '静岡県富士宮市小泉 1111111'),
    (1, '418-0022',  22, '静岡県富士宮市小泉 1-1-1 sample 103 号室'),
    (2, '418-0022',  22, '静岡県富士宮市小泉 22222222'),
    (2, '418-0022',  22, '静岡県富士宮市小泉 2-2-2 sample 103 号室'),
    (3, '418-0022',  22, '静岡県富士宮市小泉 33333333'),
    (3, '418-0022',  22, '静岡県富士宮市小泉 3-3-3 sample 103 号室'),
    (4, '418-0022',  22, '静岡県富士宮市小泉 44444444')
;
INSERT INTO sample.merchants
(merchant_id,password,name,tel,mail,zip,pref_code,address,created_user)
VALUES
(1,'$2y$10$5mS/fneIqTQ38E0hZjbcB.kBHWMYcUBt49WLJjbaAU.49b76TvKyG','会社テスト','0000000000','dummy@dummy.co.jp',
'4180022','22','静岡県富士宮市小泉2222222','system'),
(2,'$2y$10$5mS/fneIqTQ38E0hZjbcB.kBHWMYcUBt49WLJjbaAU.49b76TvKyG','会社テスト2','0000000000','dummy@dummy.co.jp',
    '4180022','22','静岡県富士宮市小泉2222222','system')
;

INSERT INTO sample.merchant_x_users
(merchant_id,user_id,is_admin,created_user)
VALUES
(1, 1, true, 'system'),
(1, 2, false, 'system'),
(2, 3, true, 'system'),
(2, 4, true, 'system')
;
