# ForWindows
このPJはWindowユーザーの場合、VirtualBoxとVagrantを用いることを前提としています。  

    https://www.virtualbox.org/wiki/Downloads  
    https://www.vagrantup.com/downloads  
上記からそれぞれDLください。  
また。本PJにおいて、windowsホスト側にシンボリックリンク作成の許可が必要です。  
cmdやbashなどで下記コマンド例等でシンボリックリンク作成の許可設定をします。

    fsutil behavior set SymlinkEvaluation L2L:1 R2R:1 L2R:1 R2L:1

上記コマンド発行後OSの再起動が必要です。

本PJに同梱されているvagrantFileに設定内容が記載されています。  
まず確認するだけであれば編集の必要はないです。  
別PJでの使用目的の場合,変更が必要な箇所がありますので確認ください。
## boxについて
 vagrantCloud上に作成済みのCentOs7にdockerをのせたものを使用します。
 https://app.vagrantup.com/yuichi-sano/boxes/centOs7onDocker

## 必要なplugin(参照しているboxにはインストール済みなので下記は実行する必要がないかもしれない。)
     vagrant plugin install vagrant-vbguest
     vagrant vbguest
## vagrantの起動
     vagrant up --provision(初回
     vagrant up(初回以降
## vagrantの停止
     vagrant halt
## vagrantの廃棄
     vagrant destroy

