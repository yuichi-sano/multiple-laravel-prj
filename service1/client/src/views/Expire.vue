<template>
  <div id="systemDialog">
    <div>
      <div class="systemDialogTitle">401 Unauthorized</div>
      <div class="systemDialogText">セッションが切れました。再度ログインしてください。</div>
      <button @click="toSignIn()">ログインページへ戻る</button>
    </div>
  </div>
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
