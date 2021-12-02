<template>
  <div id="systemDialog">
    <div>
      <div class="systemDialogTitle">404 Page Not Found</div>
      <div class="systemDialogText">指定したページは存在しません。</div>
      <button class="button" @click="toTop()">トップページへ戻る</button>
    </div>
  </div>
</template>

<script lang="ts">
  import {Component, Vue} from 'vue-property-decorator';

  @Component
  export default class NotFound extends Vue {
    // method
    toTop(): void {
      this.$router.push({path: '/'}).catch(() => {
        // @ts-ignore block is empty.
      });
    }
  }
</script>
