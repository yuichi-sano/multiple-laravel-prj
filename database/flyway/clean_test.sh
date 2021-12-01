#!/usr/bin/env bash

BASE_DIR=`dirname $0`

cd $BASE_DIR

./gradlew -i clean flywayClean processResources -Dflyway.url=jdbc:postgresql://host.docker.internal:5433/sampletest
