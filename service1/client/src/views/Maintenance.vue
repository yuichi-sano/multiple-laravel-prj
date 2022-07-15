<template>
  <b-container id="system-error" class="mt-5" data-cy="">
    <b-row class="pt-5">
      <b-col cols="12" class="text-center">
        <div class="error-message p-4 border border-danger rounded-lg">
          <h5 class="systemDialogTitle mb-3"><b-icon icon="exclamation-circle-fill" variant="danger"></b-icon>503 Service Unavailable</h5>
          <div class="systemDialogText">本システムのメンテナンス中、または対向システムが一時的に停止しているようです。しばらくお待ち下さい</div>
        </div>
      </b-col>
    </b-row>
  </b-container>
</template>

<script lang="ts">
  import {Component, Vue} from 'vue-property-decorator';

  @Component
  export default class Maintenance extends Vue {
  }
</script>


