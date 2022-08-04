FROM node:12
#working directory
WORKDIR /app
ENV PROJDIR=/app/
RUN apt-get update && apt-get install -y git curl unzip procps time
ARG IDEURL=https://download.jetbrains.com/idea/ideaIU-2022.1.1.tar.gz
#Download & Install IntelliJ IDEA
RUN curl -fsSL -o /ide.tar.gz $IDEURL && \
mkdir /ide && \
tar xfz /ide.tar.gz --strip-components=1 -C /ide && \
rm /ide.tar.gz

#Install plugin
RUN time /ide/bin/remote-dev-server.sh installPlugins $PROJDIR com.intellij.ja && \
   time /ide/bin/remote-dev-server.sh installPlugins $PROJDIR 7125-grep-console&& \
   time /ide/bin/remote-dev-server.sh installPlugins $PROJDIR 164-ideavim
#CMD /ide/bin/remote-dev-server.sh run $PROJDIR --listenOn 0.0.0.0 --port 5993

#shファイルをコンテナにコピー
COPY ./scripts/docker.start.sh /scripts/start.sh

#shフォルダの権限追加（全員実行可）
RUN chmod +x /scripts/*

#初期実行
ENTRYPOINT [ "/scripts/start.sh" ]