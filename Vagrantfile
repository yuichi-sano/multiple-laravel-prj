# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.

###########################################################################
# sail-mutipleという記述の部分はprojectに合わせて適宜命名ください #
###########################################################################

Vagrant.configure("2") do |config|

  # centOs7,docker,docker-composeをインストール済みのボックスですプロバイダはvboxのみのサポートです
  config.vm.box = "yuichi-sano/centOs7onDocker"
  # private ip 設置---不必要であればコメントアウトしてください。
  config.vm.network "private_network", ip: "192.168.33.202"

  # vbguestのVBGuestAdditionの自動アップデート無効化
  config.vbguest.auto_update = false
  # ポートフォワード設定
  config.vm.network "forwarded_port", host_ip:"127.0.0.1", guest: 2375, host: 2475 # docker-d
  config.vm.network "forwarded_port", host_ip:"127.0.0.1", guest: 21, host: 21 # ftp
  config.vm.network "forwarded_port", host_ip:"127.0.0.1", guest: 80, host: 80 # http
  config.vm.network "forwarded_port", host_ip:"127.0.0.1", guest: 5080, host: 5080 # http2
  config.vm.network "forwarded_port", host_ip:"127.0.0.1", guest: 443, host: 443 # https
  config.vm.network "forwarded_port", host_ip:"127.0.0.1", guest: 5432, host: 5432 # psql
  config.vm.network "forwarded_port", host_ip:"127.0.0.1", guest: 18080, host: 18080 # node1
  config.vm.network "forwarded_port", host_ip:"127.0.0.1", guest: 5993, host: 5993 # node-IDE
  config.vm.network "forwarded_port", host_ip:"127.0.0.1", guest: 18089, host: 18089 # locust
  config.vm.network "forwarded_port", host_ip:"127.0.0.1", guest: 18000, host: 18000 # swagger-ui
  config.vm.network "forwarded_port",guest: 18989, host: 18989 # proxyServe
  # デフォルトを無効化し、homeにてアプリケーションを稼働させる
  config.vm.synced_folder ".", "/vagrant", disabled: true,  mount_options: ['dmode=755','fmode=644']

  config.vm.synced_folder ".", "/home/apps", create:true, owner: "vagrant", group:"wheel", mount_options: ['dmode=777','fmode=777']

  # VitrualBoxの設定、仮想マシンスペック--name,cpu,memoryは良しなに設定ください
  config.vm.provider "virtualbox" do |vb|
    # 表示名、GUIの使用、CPU数、メモリ
    vb.name = "multipul-web-service"
    vb.gui = false
    vb.cpus = "4"
    vb.memory = "8192"
  end
end
