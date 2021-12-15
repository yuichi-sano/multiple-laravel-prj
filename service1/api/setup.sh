#!bin/bash

DIR=`pwd`
ENV_NAME=.env

ENV_ORIGIN=$1
SERVICE_NAME=$2
APP_URL=$3

declare -A ITEMS=(
  ["APP_SERVICE="]=$SERVICE_NAME
  ["APP_NAME="]=$SERVICE_NAME
  ["APP_URL="]=$APP_URL
)
#envファイル作成
if [ ! -e ./${ENV_NAME} ]; then
  if [[ "staging" == "$ENV_ORIGIN" ]] ; then
    cp -f ./.env.staging ./$ENV_NAME
  elif [[ "local" == "$ENV_ORIGIN" ]] ; then
    cp -f ./.env.local ./$ENV_NAME
  elif [[ "product" == "$ENV_ORIGIN" ]] ; then
    cp -f ./.env.product ./$ENV_NAME
  else
    echo 1st arg need to be staging, local or product.
  fi
  if [ ! -e ./${ENV_NAME} ]; then
    echo ${ENV_NAME} not found
    exit
  fi
fi

#ファイルのパラメータ修正
for item in "${!ITEMS[@]}"; do

  PARAM="${ITEMS[${item}]}"

  #パラメーターが設定されているか確認
  if [ -n "$PARAM"  ] ; then
    #docker-compose.ymlファイル更新
    if [[ "$item" == "APP_SERVICE=" ]]; then
      sed -e "s/laravel.test/${PARAM}/" ./docker-compose.yml > tmpfile2
      mv tmpfile2 ./docker-compose.yml
    fi

    #対象の項目が何行目にあるか確認。複数行の場合はじめの行を対象
    LINE_ORIGIN=`sed -n "/${item}/=" ./$ENV_NAME`
    LINE=`echo ${LINE_ORIGIN} | sed -e 's/ .*//'`

    #envファイル更新
    if [ -z $LINE ]; then
      #１行目にパラメータを追加
      sed -i "1i${item}${PARAM}" ./$ENV_NAME

    else
      sed ${LINE}d ./$ENV_NAME >> tmpfile3
      mv tmpfile3 ./$ENV_NAME
      #行追加
      sed -i "${LINE}i${item}${PARAM}" ./$ENV_NAME
    fi
  fi
done

if [ "$(uname)" == "Darwin" ]; then
  sh ./setup_scripts/for_mac/initial_composer.sh
  sh ./setup_scripts/docker-volume-init.sh
elif [ "$(expr substr $(uname -s) 1 5)" == "MINGW" ]; then
  echo Windowsホストは対応していません
elif [ "$(expr substr $(uname -s) 1 5)" == "Linux" ]; then
  sh ./setup_scripts/for_vagrant/initial_composer.sh
  sh ./setup_scripts/docker-volume-init.sh
else
  echo Unknown OS
fi

#エイリアス登録
#touch ~/.bash_profile
#RS=`grep -c ./vendor/bin ~/.bash_profile`
#if [ "${RS}" -eq 0 ]; then
#  echo "export PATH=./vendor/bin:"'$PATH' >> ~/.bash_profile
#fi
