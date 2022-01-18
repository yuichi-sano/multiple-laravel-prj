DROP SCHEMA IF EXISTS sample CASCADE;

CREATE SCHEMA sample;


-- master
DROP TABLE IF EXISTS sample.master;
CREATE TABLE sample.master (
  master_type CHAR(1) NOT NULL CHECK(master_type IN ('1', '2')),
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_user VARCHAR(10) NOT NULL DEFAULT '',
  CONSTRAINT sample_master_pkey PRIMARY KEY (master_type)
);

COMMENT ON TABLE sample.master IS 'sample-master';
COMMENT ON COLUMN sample.master.master_type IS 'master-type';
COMMENT ON COLUMN sample.master.start_date IS '開始日';
COMMENT ON COLUMN sample.master.end_date IS '終了日';
COMMENT ON COLUMN sample.master.created_at IS '登録日時';
COMMENT ON COLUMN sample.master.created_user IS '登録者';

-- exam 試験
DROP TABLE IF EXISTS sample.grants;
CREATE TABLE sample.grants (
    grant_id INTEGER NOT NULL,
    name     VARCHAR(10) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_user VARCHAR(10) NOT NULL DEFAULT '',
    CONSTRAINT sample_grants_pkey PRIMARY KEY (grant_id)
);

COMMENT ON TABLE sample.grants IS 'sample-master';
COMMENT ON COLUMN sample.grants.grant_id IS 'master-type';
COMMENT ON COLUMN sample.grants.name IS '開始日';
COMMENT ON COLUMN sample.grants.start_date IS '開始日';
COMMENT ON COLUMN sample.grants.end_date IS '終了日';
COMMENT ON COLUMN sample.grants.created_at IS '登録日時';
COMMENT ON COLUMN sample.grants.created_user IS '登録者';

-- user sampleユーザー
DROP TABLE IF EXISTS sample.users;
CREATE TABLE sample.users (
  user_id INTEGER NOT NULL,
  access_id VARCHAR(7) NOT NULL,
  password VARCHAR(256) NOT NULL,
  expires_at TIMESTAMP NOT NULL,
  readonly_flag BOOLEAN NOT NULL DEFAULT FALSE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_user VARCHAR(10) NOT NULL DEFAULT '',
  CONSTRAINT users_pkey PRIMARY KEY (user_id),
  CONSTRAINT users_ukey UNIQUE (access_id)
);

COMMENT ON TABLE sample.users IS 'sampleユーザー';
COMMENT ON COLUMN sample.users.user_id IS 'sampleユーザーID';
COMMENT ON COLUMN sample.users.access_id IS 'sample認証用ID';
COMMENT ON COLUMN sample.users.password IS 'パスワード';
COMMENT ON COLUMN sample.users.expires_at IS '有効日時';
COMMENT ON COLUMN sample.users.readonly_flag IS '読み取り専用フラグ';
COMMENT ON COLUMN sample.users.created_at IS '登録日時';
COMMENT ON COLUMN sample.users.created_user IS '登録者';


-- sample-user-grants
DROP TABLE IF EXISTS sample.user_grants;
CREATE TABLE sample.user_grants (
    user_id INTEGER NOT NULL,
    grant_id INTEGER NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_user VARCHAR(10) NOT NULL DEFAULT '',
    CONSTRAINT user_grants_ukey UNIQUE (user_id,grant_id),
    CONSTRAINT user_grants_fkey FOREIGN KEY (user_id)
        REFERENCES sample.users (user_id)
        ON UPDATE NO ACTION ON DELETE CASCADE,
    CONSTRAINT user_grants_fkey2 FOREIGN KEY (grant_id)
        REFERENCES sample.grants (grant_id)
        ON UPDATE NO ACTION ON DELETE CASCADE
);

COMMENT ON TABLE sample.user_grants IS 'sampleユーザー';
COMMENT ON COLUMN sample.user_grants.user_id IS 'sampleユーザーID';
COMMENT ON COLUMN sample.user_grants.grant_id IS 'ユーザー権限';
COMMENT ON COLUMN sample.user_grants.created_at IS '登録日時';
COMMENT ON COLUMN sample.user_grants.created_user IS '登録者';


-- userが持つアドレス
DROP TABLE IF EXISTS sample.user_addresses;
CREATE TABLE sample.user_addresses (
    user_id INTEGER NOT NULL,
    zip     CHAR(8) NOT NULL,
    pref_code VARCHAR(2) NOT NULL,
    address text NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_user VARCHAR(10) NOT NULL DEFAULT '',
    CONSTRAINT user_addresses_ukey UNIQUE (user_id,address),
    CONSTRAINT user_addresses_fkey FOREIGN KEY (user_id)
        REFERENCES sample.users (user_id)
        ON UPDATE NO ACTION ON DELETE CASCADE
);

COMMENT ON TABLE sample.user_addresses IS 'sampleユーザー';
COMMENT ON COLUMN sample.user_addresses.user_id IS 'sampleユーザーID';
COMMENT ON COLUMN sample.user_addresses.zip IS '郵便番号';
COMMENT ON COLUMN sample.user_addresses.pref_code IS '県コード';
COMMENT ON COLUMN sample.user_addresses.address IS '県名市町村含む住所';
COMMENT ON COLUMN sample.user_addresses.created_at IS '登録日時';
COMMENT ON COLUMN sample.user_addresses.created_user IS '登録者';


DROP TABLE IF EXISTS sample.user_refresh_tokens;
CREATE TABLE sample.user_refresh_tokens (
  refresh_token VARCHAR(256) NOT NULL,
  user_id INTEGER NOT NULL,
  expires_at TIMESTAMP NOT NULL,
  signs_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_user VARCHAR(10) NOT NULL DEFAULT '',
  CONSTRAINT user_refresh_tokens_pkey PRIMARY KEY (refresh_token),
  CONSTRAINT user_refresh_tokens_fkey FOREIGN KEY (user_id)
    REFERENCES sample.users (user_id)
      ON UPDATE NO ACTION ON DELETE CASCADE
);

COMMENT ON TABLE sample.user_refresh_tokens IS 'sampleユーザーリフレッシュトークン';
COMMENT ON COLUMN sample.user_refresh_tokens.refresh_token IS 'リフレッシュトークン';
COMMENT ON COLUMN sample.user_refresh_tokens.user_id IS 'sampleユーザーID';
COMMENT ON COLUMN sample.user_refresh_tokens.expires_at IS '有効日時';
COMMENT ON COLUMN sample.user_refresh_tokens.signs_at IS '最終ログイン日時';
COMMENT ON COLUMN sample.user_refresh_tokens.created_at IS '登録日時';
COMMENT ON COLUMN sample.user_refresh_tokens.created_user IS '登録者';

DROP TABLE IF EXISTS sample.user_profiles;
CREATE TABLE sample.user_profiles (
    user_profile_id SERIAL,
    user_id INTEGER NOT NULL,
    name    VARCHAR(256) NOT NULL,
    tel     VARCHAR(15) NOT NULL,
    mail    VARCHAR(256) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_user VARCHAR(10) NOT NULL DEFAULT '',
    CONSTRAINT user_profiles_pkey PRIMARY KEY (user_profile_id),
    CONSTRAINT user_profiles_fkey FOREIGN KEY (user_id)
        REFERENCES sample.users (user_id)
        ON UPDATE NO ACTION ON DELETE CASCADE
);

COMMENT ON TABLE sample.user_profiles IS 'ユーザー詳細情報';
COMMENT ON COLUMN sample.user_profiles.user_profile_id IS 'サロゲート';
COMMENT ON COLUMN sample.user_profiles.user_id IS 'sampleユーザーID';
COMMENT ON COLUMN sample.user_profiles.name IS '名前';
COMMENT ON COLUMN sample.user_profiles.tel IS '電話番号';
COMMENT ON COLUMN sample.user_profiles.mail IS 'メールアアドレス';
COMMENT ON COLUMN sample.user_profiles.created_at IS '登録日時';
COMMENT ON COLUMN sample.user_profiles.created_user IS '登録者';

DROP TABLE IF EXISTS sample.merchants;
CREATE TABLE sample.merchants (
    merchant_id INTEGER NOT NULL,
    password    VARCHAR(256) NOT NULL,
    name        VARCHAR(256) NOT NULL,
    tel         VARCHAR(15) NOT NULL,
    mail        VARCHAR(256) NOT NULL,
    zip         CHAR(8) NOT NULL,
    pref_code   VARCHAR(2) NOT NULL,
    address     text NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_user VARCHAR(10) NOT NULL DEFAULT '',
    CONSTRAINT user_merchants_pkey PRIMARY KEY (merchant_id)
);

COMMENT ON TABLE sample.merchants IS 'ユーザー詳細情報';
COMMENT ON COLUMN sample.merchants.merchant_id IS 'サロゲート';
COMMENT ON COLUMN sample.merchants.name IS '名前';
COMMENT ON COLUMN sample.merchants.password IS 'パスワード';
COMMENT ON COLUMN sample.merchants.tel IS '電話番号';
COMMENT ON COLUMN sample.merchants.mail IS 'メールアアドレス';
COMMENT ON COLUMN sample.merchants.zip IS '郵便番号';
COMMENT ON COLUMN sample.merchants.pref_code IS '県コード';
COMMENT ON COLUMN sample.merchants.address IS '県名市町村含む住所';
COMMENT ON COLUMN sample.merchants.created_at IS '登録日時';
COMMENT ON COLUMN sample.merchants.created_user IS '登録者';

-- 企業-ユーザーリレーション
DROP TABLE IF EXISTS sample.merchant_x_users;
CREATE TABLE sample.merchant_x_users (
    merchant_id INTEGER NOT NULL,
    user_id     INTEGER NOT NULL,
    is_admin    BOOLEAN NOT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_user VARCHAR(10) NOT NULL DEFAULT '',
    CONSTRAINT merchant_x_users_fkey FOREIGN KEY (merchant_id)
        REFERENCES sample.merchants (merchant_id)
        ON UPDATE NO ACTION ON DELETE CASCADE,
    CONSTRAINT merchant_x_users_fkey2 FOREIGN KEY (user_id)
        REFERENCES sample.users (user_id)
        ON UPDATE NO ACTION ON DELETE CASCADE
);

COMMENT ON TABLE sample.merchant_x_users IS '企業-ユーザーリレーション';
COMMENT ON COLUMN sample.merchant_x_users.merchant_id IS '企業ID';
COMMENT ON COLUMN sample.merchant_x_users.user_id IS 'ユーザーID';
COMMENT ON COLUMN sample.merchant_x_users.is_admin IS '管理者権限';
COMMENT ON COLUMN sample.merchant_x_users.created_at IS '登録日時';
COMMENT ON COLUMN sample.merchant_x_users.created_user IS '登録者';
