#!bin/bash

DIR=`pwd`
ENV_NAME=.env
SCRIPT_PATH=$(dirname $0)
ENV_ORIGIN=$1
SERVICE_NAME=$2
APP_URL=$3

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


if [ "$(uname)" == "Darwin" ]; then
    # ファイルのパラメータ修正
    edit_environment(){
      item=$1
      PARAM=$2
        #パラメーターが設定されているか確認
        if [ -n "$PARAM"  ] ; then
          #docker-compose.ymlファイル更新
          if [[ "$item" == "APP_SERVICE=" ]]; then
            if [ -e ./docker-compose.yml ]; then
              sed -e "s/laravel.test/${PARAM}/" ./docker-compose.yml > tmpfile2
              mv tmpfile2 ./docker-compose.yml
            fi
          fi
          #対象の項目が何行目にあるか確認。複数行の場合はじめの行を対象
          LINE_ORIGIN=`sed -n "/${item}/=" ./$ENV_NAME`
          LINE=`echo ${LINE_ORIGIN} | sed -e 's/ .*//'`
          #envファイル更新
          if [ -z $LINE ]; then
            #１行目にパラメータを追加
            sed -i '' -e $"1s/^/${item}${PARAM}\\n/" "./${ENV_NAME}"
          else
            sed ${LINE}d ./$ENV_NAME >> tmpfile3
            mv tmpfile3 ./$ENV_NAME
            #行追加
            sed -i '' -e $"${LINE}s/^/${item}${PARAM}\\n/" "./${ENV_NAME}"
          fi
        fi
    }
    edit_environment 'APP_SERVICE=' $SERVICE_NAME
    edit_environment 'APP_NAME='    $SERVICE_NAME
    edit_environment 'APP_URL='     $APP_URL
  sh $SCRIPT_PATH/for_mac/initial_composer.sh
  sh $SCRIPT_PATH/docker-volume-init.sh
elif [ "$(expr substr $(uname -s) 1 5)" == "MINGW" ]; then
  echo Windowsホストは対応していません
elif [ "$(expr substr $(uname -s) 1 5)" == "Linux" ]; then

  declare -A ITEMS=(
    ["APP_SERVICE="]=$SERVICE_NAME
    ["APP_NAME="]=$SERVICE_NAME
    ["APP_URL="]=$APP_URL
  )
  #ファイルのパラメータ修正
  for item in "${!ITEMS[@]}"; do

    PARAM="${ITEMS[${item}]}"

    #パラメーターが設定されているか確認
    if [ -n "$PARAM"  ] ; then
      #docker-compose.ymlファイル更新
      if [[ "$item" == "APP_SERVICE=" ]]; then
        if [ -e ./docker-compose.yml ]; then
          sed -e "s/laravel.test/${PARAM}/" ./docker-compose.yml > tmpfile2
          mv tmpfile2 ./docker-compose.yml
        fi
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
  sh $SCRIPT_PATH/for_vagrant/initial_composer.sh
  sh $SCRIPT_PATH/docker-volume-init.sh
else
  echo Unknown OS
fi

