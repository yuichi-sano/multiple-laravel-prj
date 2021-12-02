<template>
  <h1 @click="toTop()">
    <span><img src="/img/header/logo.png" alt="SAMPLE"><img src="/img/header/header_org.png" alt="SAMPLE"></span>
    <strong><img src="/img/header/header_title.png" alt="SAMPLE"></strong>
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
