<template>
  <b-container id="systemDialog" class="mt-5" data-cy="">
    <b-row class="pt-5">
      <b-col cols="12" class="text-center">
        <div class="error-message p-4 border border-danger rounded-lg">
          <h5 class="systemDialogTitle mb-3"><b-icon icon="exclamation-circle-fill" variant="danger"></b-icon> 401 Unauthorized</h5>
          <div class="systemDialogText mb-3" >セッションが切れました。再度ログインしてください</div>
          <b-button variant="success" @click="toSignIn()">ログインページへ戻る</b-button>
        </div>
      </b-col>
    </b-row>
  </b-container>
</template>

<script lang="ts">
  import {Component, Vue} from 'vue-property-decorator';
  import {tokenModule} from '@/stores/authentication/Authentication';
  import {loginModule} from '@/stores/authentication/Account';

  @Component
  export default class Expire extends Vue {
    // method
    toSignIn(): void {
      this.$router.push({path: '/signin'}).catch(() => {
        // @ts-ignore block is empty.
      });
    }

    // life cyclehook
    public created(): void {
      tokenModule.clear();
      loginModule.clear();
    }
  }
</script>
