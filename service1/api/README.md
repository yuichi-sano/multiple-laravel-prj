# DDD-Laravel-Doctorine

# SetUp
## git clone
まずはこのPJをGitClone、または一括でDLしてください
### cloneの場合 
	git clone https://github.com/yuichi-sano/ddd-laravel-doctrine.git  
	# 他のPJで使う場合は下記のようにgit管理を削除
	rm -rf .git

### DLの場合
	wget https://github.com/yuichi-sano/ddd-laravel-doctrine/archive/refs/heads/master.zip a.zip; unzip a.zip

## ForWindows
[Windows環境でのSETUP](./docs/for_win/README.md)
本資料では、vagrantに関する記述は以上とします。
vagrantにて仮想環境が起動したら、ssh接続を実施し
setup.shを実行ください。
## ForMac
setup.shを実行ください。

## Sailの実行
./vendor/bin/sail up -d
にてアプリケーションが起動します。
ここまででSETUPが完了します。

# DDD戦略について
   本PJではDDDの戦略としてクリーンアーキテクトを取り入れています。
   Laravelそれ自体のドキュメントからでは追いづらい部分を本資料で補足していこうと思います。
## packagesについて

## doctorineについて
### migration
	Eloquentは廃止しています・。
	doctorine一本なのですが。migrationはそもそもlaravelの機能をつかっていません。
	mifrationについては後述します。




# Migrationについて
 Flywayというツールを用いています。
 これはPHP以外の標準開発にflywayを導入するためです。
 FlyWayようの各種artisanのカスタマイズは実施済みです。
 用はすべてgradlew経由でmigrationを実施していきます。

