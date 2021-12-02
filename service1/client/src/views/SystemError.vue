<template>
  <div id="systemDialog">
    <div>
      <div class="systemDialogTitle">500 System Error</div>
      <div class="systemDialogText">システムエラーです。システム管理者へお問い合わせください。</div>
    </div>
  </div>
</template>

<script lang="ts">
  import {Component, Vue} from 'vue-property-decorator';

  @Component
  export default class SystemError extends Vue {
  }
</script>
