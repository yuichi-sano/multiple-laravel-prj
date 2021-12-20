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

	cd /home/sail-mutiple/service1/api/
	sh setup.sh local service1 localhost
を実行ください。
## ForMac
本PJに同梱されるシェルではrealpathコマンドを使用しています。  
macでは下記パッケージが必要なのでinstallします。
    brew install bash
    brew install coreutils
上記は主にbundle-sailというスクリプト実行に必要です。  
インストールが完了次第後続の手順へ進んでください

	cd service1/api/
	sh setup.sh local service1 localhost
を実行ください。
※パーミッションの件確認
### setup.shについて
    第一引数: 環境を入力。local,staging,productの3種類を想定しています。
    第二引数: サービス名称を入力、dockerのコンテナ名にはねます
    第三引数: URL,開発時点ではlocalhostを指定しておけば問題ないです。

## BundleSailの実行
laravel公式が提供しているsailを少しだけ拡張しています。  
複数のsailプロジェクトを同時に扱うためのコマンドを用意しており、それを本PJでは

    bundle-sail
と呼んでいます。
下記手順にて実行します。
リポジトリのTOPディレクトリまで移動します。

	cd {このREADME.mdのpath}
	./bin/bundle-sail up -d 

にてアプリケーションが起動します。

    sudo ln -s /home/sail-mutiple/bin/bundle-sail /usr/bin/bundle-sail
など登録しておくとよいと思います。  
※基本的には場所を選ばずに実行するようにしております。

ここまででSETUPが完了します。
### SETUP-TIPS
この手順ではDBのmigrationを省いています。 

    bundle-sail service1 artisan flyway:develop
などでマグレーションを実施するとよいです。

初期設定のserviceの起動が完了すると

http://localhost:18080/

からclient画面を確認できます。  
またlaraveアプリは下記で動作します。

http://localhost:5080/

# Serviceの追加
sail プロジェクトを追加するパターンについて言及します。
bundle-sail に追加用のスクリプトを用意しています。

	bundle-sail add_service [サービス名] [環境] [URL]

上記を実施すると、service1のほかに指定したサービス名にて同様の構成が作成されます。 

※https://github.com/yuichi-sano/ddd-laravel-doctrine

とほぼ同等のソースが配置され,setupも完了した状態になります。
### bundle-sailの引数について
    第一引数: サービス名称を入力、dockerのコンテナ名にはねます
    第二引数: 環境を入力。local,staging,productの3種類を想定しています。
    第三引数: URL,開発時点ではlocalhostを指定しておけば問題ないです。

※setup.shと第一、第二の引数順番が逆ですのでご注意ください。

## TODO 
	docker-compose.yml
	bundle.env
には現状手動で追記が必要です。それぞれ追加したアプリケーション名にそって記述を追加します。

※上記ファイルそれぞれのservice1に関する記述を模倣するとよいです。

    bundle-sail up -d 
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

    ./database/flyway   
に関してはmulti構成の場合はservice個別に集中データベースのマグレーション管理  
をしだすと最終的に管理しきれなくなることが考えられるためです。  
また、docker-composeを下記のように書き換えます、  

	./docker-compose.yml [指定したサービス名].yml  

このymlを参考に、本PJが参照するdocker-composeへ追記していくとよいと思います。  


