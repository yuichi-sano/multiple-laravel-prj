# JAAI application Trade Skill Tests Client

- [npm](https://docs.npmjs.com/)
- [Gradle](https://docs.gradle.org/current/userguide/userguide.html)

## Usage

- Gradleタスクから起動、静的解析、ビルドを実行します。

### setup
```
$ cd ../
./gradlew client:npmInstall
```

### run
```
$ cd ../
./gradlew client:jsServe
```

- http://localhost:18080 で起動します。

#### mock mode

- バックエンドのAPIの代わりにMockからデータを取得します。
- ルートに`.env.development.local`を作成し、`VUE_APP_USE_MOCK=true`にした後に起動してください。
- `Mock.ts`に定義されているデータを取得するようになります。そのためバックエンドAPIの起動は不要となります。

### lint

```bash
$ cd ../
$ ./gradlew client:jsLint
```

### build
 
```bash
$ cd ../
$ ./gradlew client:clean client:jsBuild
```

/dist配下に静的リソースがbuildされます。

### Reference

- [Vue.js](https://cli.vuejs.org)

## Design

- filter
  - global filterの定義
  - main.tsでfilterを追加
- assets
  - scss, imagesの配置
  - 基本的にはコンテンツチームが生成
- components
  - vueのコンポーネントを定義
  - Atomic Designを模しているが定義が曖昧かも
- infrastructure
  - 外部との連携（主にバックエンドのAPI）
- stores
  - 状態管理を定義
- validator
  - 入力チェックなどバリデーションを定義
- types
  - 型の定義
  - どのレイヤーからも定義された型を利用して品質の担保を実現

![Package](../../doc/client-package.png)
