<template>
  <header>
    <div class="headerLeft">
      <Logo/>
    </div>
    <div class="headerRight" v-show="loggedIn">
      <button @click="toMyPage()"
              data-cy="マイページボタン">マイページに戻る</button>
      <button @click="openConfirmModal()">ログアウト</button>
      <ConfirmModal name="LogoutConfirmModal" title="ログアウト" @confirm="logout($event)">
        <template slot="content">
          <p>{{message}}</p>
        </template>
      </ConfirmModal>
    </div>
    <div class="headerInfo" v-show="loggedIn">
      <div class="headerInfoLeft">
        <p class="headerInfo_id"
           data-cy="ようこそ"></p>
        <p class="headerInfo_limit"
           data-cy="ようこそ2"></p>
      </div>
      <div class="headerInfoRight">
      </div>
    </div>
  </header>
</template>

<script lang="ts">
  import {Component, Watch, Vue} from 'vue-property-decorator';
  import {loginModule} from '@/stores/authentication/Account';
  import {tokenModule} from '@/stores/authentication/Authentication';
  import ConfirmModal from '@/components/molecules/modal/ConfirmModal.vue';
  import Logo from '@/components/atoms/Logo.vue';
  import api from '@/infrastructure/api/API';

  @Component({
    components: {
      ConfirmModal,
      Logo,
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
        loginModule.getAccount();
        this.setSignedCookies();
      }
    }

    // computed
    get examineeNumber(): string {
      return loginModule.examineeNumber;
    }

    get hasUnreadAnswer(): boolean {
      return loginModule.hasUnreadAnswer;
    }
    get hasUserInquiry(): boolean {
      return loginModule.hasUserInquiry;
    }

    get title(): string {
      return this.$router.currentRoute.meta.title;
    }

    get fullTitle(): string {
      if (!this.title) {
        return 'SAMPLE';
      }
      return 'SAMPLE | ' + this.title;
    }

    // method
    initialize(): void {
      document.title = this.fullTitle;
      this.loggedIn = this.$router.currentRoute.meta.requiresAuth;
    }

    openConfirmModal(): void {
      this.$modal.show('LogoutConfirmModal');
    }

    closeConfirmModal(): void {
      this.$modal.hide('LogoutConfirmModal');
    }

    toMyPage(): void {
      this.$router.push({path: '/'}).catch(() => {
        // @ts-ignore block is empty.
      });
    }

    toInquiryForm(): void {
      this.$router.push({path: '/inquiry/form'}).catch(() => {
        // @ts-ignore block is empty.
      });
    }

    toInquiry(): void {
      this.$router.push({path: '/inquiry'}).catch(() => {
        // @ts-ignore block is empty.
      });
    }

    logout(confirmed: boolean): void {
      if (confirmed) {
        tokenModule.clear();
        loginModule.clear();
        this.$router.push({path: '/signin'});
      }
      this.closeConfirmModal();
    }

    setSignedCookies(): void {
      api.setSignedCookies();
    }

    // lifecycle hooks
    created(): void {
      this.initialize();
    }
  }
</script>
