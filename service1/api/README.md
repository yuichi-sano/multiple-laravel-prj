# DDD-Laravel-Doctrine

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
※本資料では、vagrantに関する記述は以上とします。  
vagrantにて仮想環境が起動したら、ssh接続を実施し
setup.shを実行ください。

    sh ./setup_scripts/setup.sh local service1 localhost
## ForMac
setup.shを実行ください。

    sh ./setup_scripts/setup.sh local service1 localhost
### setup.shについて
    第一引数: 環境を入力。local,staging,productの3種類を想定しています。
    第二引数: サービス名称を入力、dockerのコンテナ名にはねます
    第三引数: URL,開発時点ではlocalhostを指定しておけば問題ないです。

## Sailの実行

    ./vendor/bin/sail up -d
にてアプリケーションが起動します。
ここまででSETUPが完了します。

# 設計思想
   本PJでは戦略的ドメイン駆動開発をするためにLaravelを使ったクリーンアーキテクトを取り入れており  
   Laravelそれ自体のドキュメントを読みながら一からクリーンアーキテクトを実践するとコストがかかりがちな部分を予め用意しております。  
   したがって、Laravelとはまた違った実装上の決まり事や思想が注入されており、ここではそれらについて解説していきます。
## packagesについて
本PJのディレクトリ構造をみていただくと、Laravelとして用意しているビジネスロジックの実装箇所である　　
app
というディレクトリと同列に  
packages  
というディレクトリが存在することがわかると思います。
このpackagesというディレクトリに下記3つのディレクトリが配置されています。

    domain
    infrastructure
    service
上記3つのディレクトリをそれぞれレイヤーとし、それぞれに対して責務与えています。
    
    domainレイヤ::業務ロジックを定ぐ
    infrastructureレイヤ::外部リソース操作を定義する
    service::実現したい機能を定義する
このうち

    infrastructure
などを例にとると想像しやすいかと思いますが、DBに対する操作、外部との連携、メール送信
等の責務を任せるようにします。  
また3つの層が互いに疎結合であるべきで、かつ他のレイヤの責務が混じらないように設計する必要があります。  
※ただし大前提としてLaravelを使用していることもあり、その縛りによって厳しい部分も多く完全には実現できてはいません  

わかり安い例でいうとdomain層にはビジネスロジックを任せていくことになりますが、  
その時domain層の中にDBに関する関心事、例えばselect句のようなsql構文を直接書  
いてしまわないように心がけて設計することで実現できます。

## Laravelとpackagesについて
ここまででpackagesディレクトリの大まかな役割を説明してきました  
今度はLaravel本体はどのような役割を持つかについて解説していきます  
packagesでは、主にアプリケーションを構成する中身についての定義を  
していくことになりますが これだけではアプリケーションは動作しません。  
アプリケーションを使用するためのインターフェースが必要です。  
本PJではデフォルトでLaravelをApi専用で使用しているのでWebApi  
を例に出すとpackagesでは、エンドポイントの定義、ルーティング、  
HTTPリクエストに基づいたコントロールについての定義がありません。  
一般的に言われるcleanArchitectにおいて上記は

    presentation
というレイヤに分類されます。  
Laravelから提供される機能はこのpresentation層の定義に強力な力  
を発揮できるものが多々あります。よってpresentation層に関しては   
Laravelにべったりと寄り添った実装をしていきます。  
また、Laravelは強力なDIコンテナの機能も提供しています。  
これは
- domain層とinfrastructure層の依存性を解決
- service層から直接infrastructure層を参照しない仕組の構築
- laravelが予め定義しているHash機能
- laravelが予め定義している認証機能の拡張
- controller層から参照するservice層の可用性の担保

などなどをシステムを構築する上で重要な役割をになっております。  
大きくは上記を抑えておけば、これから開発するアプリケーションについて、  
何のドキュメントを見ながら、どこにどのように定義していけばよいのか。  
が見えてくるかと思います。


## 設計デザイン
![design](./docs/architect/designArchitect.svg)
- [設計についての詳細](./docs/architect/designArchitect.md)
- [主要なClass設計図](./docs/architect/classesUml.svg)
# 設定済、カスタマイズ済み
Laravelについて、packages以外で特筆すべき拡張を実施している点についての説明、解説をしていきます。

## JWT認証laravel用モジュール
    Tymon/JWT-Auth
そのままレールに従った使い方ではないが、GuardなどLaravelのうまみを消しすぎないような  
カスタマイズを実施しています。   
Tokenの作成はFactoryを介して実施することでブラックボックス化をある程度軽減させています。　　
※本PJのsampleではdefaultでJWT認証に倒しています。

## Japanese
Laravelはそのまま使うとValidationエラーのメッセージに英語を返してきたりします。   
そのままでもよいにはよいですが、日本語で定義しておいた方が理解の助けになるということで導入しています。

    日本語バリデーション等追加済み
    laravelの標準的な使い方をしています
    バリデーションや、例外メッセージ等もここで管理するといいと思います。
    TODO メール文面をどこに配置するかを要件等する。
## MiddleWare
    tokenAuth
と命名して、jwt認証ガード一式を登録しています。


## Doctrineについて
	本初期sampleではEloquentは廃止しています
	LaravelDoctorineを採用しています。
    migrationはそもそもlaravelの機能をつかっていません。
	migrationについては後述します。
    本PJではxmlマッピング形式を利用しさらに
    NamedNativeQueryにて最小限のファイル構成でdomain層との完全なる疎結合を実現しています。

※DIはDatasourceProvidersに記載していきます。
- [Doctrineについてさらに詳細](./docs/architect/ORM/doctrine.md)

## hash値
    laravelがdefaultで操作できるhash値は比較的最近の技術しかなかったので
    md5,SHA256
    についてのHash値作成クラスを作成し,hasing.phpのなかでdefault指定が可能な状態にしております。
    Doctrineのユーザープロバイダには途中でHasherを設定できる状態ではなかったので。
    こちらも拡張済み

## Exception
    Validationexceptionを定義済みです。  
    WebAPIExceptionも定義ずみです。
    他に指定が無ければvalidationError時にはValidationExceptionをthrowするようにハンドルされています。

## AccessToken,RefreshToken
    AccessToken はLogin時に発行されて、適宜クライアントからサーバへリクエストください。
    RefreshTokenはAccessTokenが無効なときのみ投げつけてください。
    RefreshTokenはDBに保存してあります。
    ※こちらは開発するアプリケーションの仕様によりますので適宜変更ください
    Laravelのように極力ブラックボックス化しないような実装となっており、packagesないで変更が可能な作りになっております。


## unitTest
    laravelに備わっている標準的な単体を実施します。
    単体、結合では、DBはテスト用に向けています。
    またテストの度にテスト用DBをクリーン、マイグレーションするような仕組です。
    普段の開発の邪魔にならないかと思います。



# Migrationについて
 Flywayというツールを用いています。
 これはPHP以外の標準開発にflywayを導入するためです。
 FlyWayようの各種artisanのカスタマイズは実施済みです。
 用はすべてgradlew経由でmigrationを実施していきます。

    ./vendor/bin/sail artisan flyway:develop



#SwaggerとSwagger対応コマンドについて。
    ./vendor/bin/sail artisan make:swagger-codegen {--tag=} {--force}
を実行すると、
    resources\swagger\api.json
の内容から

    Controller
    Request
    Result
を自動で生成します
