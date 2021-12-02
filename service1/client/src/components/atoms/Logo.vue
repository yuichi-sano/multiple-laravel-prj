<template>
  <h1 @click="toTop()">
    <span><img src="/img/header/logo_jaai.png" alt="JAAI"><img src="/img/header/header_org.png" alt="一般財団法人日本自動車査定協会"></span>
    <strong><img src="/img/header/header_title.png" alt="査定士技能検定研修eラーニング"></strong>
  </h1>
</template>

<script lang="ts">
  import {Component, Vue} from 'vue-property-decorator';

  @Component({})
  export default class Logo extends Vue {
    // method
    toTop(): void {
      this.$router.push({path: '/'}).catch(() => {
        // @ts-ignore block is empty.
      });
    }
  }
</script>
