# SAMPLE application Database

## Setup

- PostgreSQL 11.9
- Docker
- [Flyway](https://flywaydb.org/) というデータベースマイグレーションツールで管理します。

### Create Database

- ローカル環境のデータベース構築及び起動はDocker Composeを実行します。 
```bash
$ cd database/docker
$ docker-compose up -d 
```

- 実行後に `sample` `sampletest` データベースが構築されます。
  - user: sample
  - password: weak-password
- localhostで接続できます。

### Migration
  
#### clean

- DBをクリアします。Create Database直後の状態となります。
```bash
$ ./clean.sh
```
- unit test用のDBをクリアする場合は別のshを実行します。
```bash
$ ./clean_test.sh
```

#### develop

- DBのクリアとマイグレーションを実施し、resources/develop配下のSQLを実行します。
```bash
$ ./develop.sh
```

#### migration

- DBのマイグレーションを実施します。
```bash
$ ./migration.sh
```
