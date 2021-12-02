<template>
  <div id="systemDialog">
    <div>
      <div class="systemDialogTitle">503 Service Unavailable</div>
      <div class="systemDialogText">メンテナンス中です。しばらくお待ち下さい。</div>
    </div>
  </div>
</template>

<script lang="ts">
  import {Component, Vue} from 'vue-property-decorator';

  @Component
  export default class Maintenance extends Vue {
  }
</script>
