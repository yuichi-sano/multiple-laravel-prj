# DDD-Laravel-Doctorine

# SetUp
## git clone
まずはこのPJをGitClone、または一括でDLしてください
### cloneの場合 
	git clone https://github.com/yuichi-sano/multiple-laravel-prj.git  
	# 他のPJで使う場合は下記のようにgit管理を削除
	rm -rf .git

### DLの場合
	wget https://github.com/yuichi-sano/multiple-laravel-prj/archive/refs/heads/main.zip
	unzip main.zip

## ForWindows
[Windows環境でのSETUP](./docs/for_win/README.md)  
本資料では、vagrantに関する記述は以上とします。  
vagrantにて仮想環境が起動したら、ssh接続を実施し  
	cd service1/api/
	sh setup.sh
を実行ください。
## ForMac
	cd service1/api/
	sh setup.sh
を実行ください。

## BundleSailの実行
laravel公式が提供しているsailを少しだけ拡張しています。  
複数のsailプロジェクトを同時に扱うためのコマンドを用意しており、それを本ＰＪでは
	bundle-sail
と呼んでいます。
下記手順にて実行します。
リポジトリのTOPディレクトリまでいどうします。
	cd {このREADME.mdのpath}
	./bin/bundle-sail up -d 
にてアプリケーションが起動します。
ここまででSETUPが完了します。


# Serviceの追加
sail プロジェクトを追加するパターンについて言及します。
bundle-sail に追加用のスクリプトを用意しています。
	./bin/bundle-sail add_service [サービス名]

上記を実施すると、service1のほかに指定したサービス名にて同様の構成が作成されます。
※https://github.com/yuichi-sano/ddd-laravel-doctrine
とほぼ同等のソースが配置され,setupも完了した状態になります。

## TODO 
	docker-compose.yml
	bundle.env
には現状手動で追記が必要です。それぞれ追加したアプリケーション名にそって記述を追加します。
※上記ファイルそれぞれのservice1に関する記述を模倣するとよいです。

./bin/bundle-sail up -d 
にてアプリケーションが起動します。
アプリが起動したらServiceの追加が完了です。

#TIP 
サービス追加時
	https://github.com/yuichi-sano/ddd-laravel-doctrine
のソースコードを落としてきます。
その後、各種設定を行ったのち
	.git
	Vagrantfile
	./database/flyway   
を削除します。  
	
に関してはmuti構成の場合はservice個別に集中データベースのマグレーション管理をしだすと管理しきれなくなることが考えられるためです。  
また、docker-composeを下記のように書き換えます、  
	./docker-compose.yml [指定したサービス名].yml  
このymlを参考に、本PJが参照するdocker-composeへ追記していくとよいと思います。  


