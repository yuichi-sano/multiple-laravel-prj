#!/usr/bin/env bash

BASE_DIR=`dirname $0`

cd $BASE_DIR

./gradlew -i clean flywayClean processResources 
