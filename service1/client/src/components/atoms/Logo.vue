<template>
  <h1 @click="toTop()">
    <span><img src="/img/header/logo.png" alt="SAMPLE" width="50" height="50"></span>
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
