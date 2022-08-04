<template>
  <header>
      <b-navbar type="dark" variant="secondary">
          <b-navbar-brand @click="toMenu()">
              SAMPLE
          </b-navbar-brand>

          <b-navbar-nav class="ml-auto" v-show="loggedIn">
              <b-button class="my-2 my-sm-0" @click="logout()">ログアウト</b-button>
          </b-navbar-nav>
      </b-navbar>
    <ConfirmModal
      :bind-confirm="bindConfirm"
      :show-detail="false"
      ref="logoutConfirm"
    >
      <template #header>
        <h5 class="font-weight-bold">ログアウト</h5>
      </template>
      <template #content>
        <div class="h6 font-weight-bold">ログアウトして宜しいでしょうか？</div>
      </template>
    </ConfirmModal>
  </header>
</template>

<script lang="ts">
import {Component, Watch, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import {tokenModule} from '@/stores/authentication/Authentication';
import Logo from '@/components/atoms/Logo.vue';
import api from '@/infrastructure/api/API';
import ConfirmModal from '@/components/molecules/modal/ConfirmModal.vue';
@Component({
  components: {
    Logo,
    ConfirmModal,
  },
})
export default class Header extends Vue {
  // data
  loggedIn: boolean = false;
  message: string = 'ログアウトします。よろしいですか？';

  @Watch('$route')
  routeUpdate() {
    this.initialize();
    if (this.loggedIn) {
      // loginModule.getAccount();
      this.setSignedCookies();
    }
  }

  // computed
  get userId(): string {
    return loginModule.userId;
  }


  get title(): string {
    return this.$router.currentRoute.meta.title;
  }

  get fullTitle(): string {
    if (!this.title) {
      return 'メンテナンスツール';
    }
    return 'メンテナンスツール | ' + this.title;
  }

  // method
  initialize(): void {
    document.title = this.fullTitle;
    this.loggedIn = this.$router.currentRoute.meta.requiresAuth;
  }
  async bindConfirm(confirmed: boolean, data: any): Promise<void> {
    if (confirmed) {
      await this.executeLogout();
    }
    // @ts-ignore
    this.$refs.logoutConfirm.hideModal();
  }

  logout() {
    // @ts-ignore
    this.$refs.logoutConfirm.showModal();
  }
  executeLogout(): void {
    tokenModule.clear();
    loginModule.clear();
    this.$router.push({path: '/signin'});
  }
  toMenu(): void {
      this.$router.push({path: '/'});
  }

  setSignedCookies(): void {
    // api.setSignedCookies();
  }

  // lifecycle hooks
  created(): void {
    this.initialize();
  }
}
</script>
