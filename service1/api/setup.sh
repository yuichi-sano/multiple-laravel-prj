#!bin/bash

if [ "$(uname)" == "Darwin" ]; then
  sh ./setup_scripts/for_mac/initial_composer.sh
  sh ./setup_scripts/docker-volume-init.sh
elif [ "$(expr substr $(uname -s) 1 5)" == "MINGW" ]; then
  echo Windowsホストは対応していません。
elif [ "$(expr substr $(uname -s) 1 5)" == "Linux" ]; then
  sh ./setup_scripts/for_vagrant/initial_composer.sh
  sh ./setup_scripts/docker-volume-init.sh
else
  echo Unknown OS
fi
