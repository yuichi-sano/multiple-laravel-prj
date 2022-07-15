<template>
  <b-container id="system-error" class="mt-5" data-cy="">
      <b-row class="pt-5">
        <b-col cols="12" class="text-center">
          <div class="error-message p-4 border border-danger rounded-lg">
            <h5 class="systemDialogTitle mb-3"><b-icon icon="exclamation-circle-fill" variant="danger"></b-icon> 500 System Error</h5>
            <div class="systemDialogText">システムエラーです。システム管理者へお問い合わせください。</div>
          </div>
        </b-col>
      </b-row>
  </b-container>
</template>

<script lang="ts">
  import {Component, Vue} from 'vue-property-decorator';

  @Component
  export default class SystemError extends Vue {
  }
</script>
