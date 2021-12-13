<template>
  <div id="login"
       data-cy="パスワード入力画面">
    <div class="login_form">
      <div class="message is-warning"
           v-show="hasError">
        <ul id="messages">
          <li v-for="message in messages" :key="message">{{message}}</li>
        </ul>
      </div>
      <h1>ログインサンプル</h1>
      <label>
        ログインID
        <input id="accessId"
               type="text"
               inputmode="text"
               class="accessId"
               placeholder="00000"
               maxlength="7"
               v-model="request.accessId"
               />
      </label>
      <label>
        パスワードを入力してください
        <input id="password" type="password" v-model="request.password"/>
      </label>
      <button id="signInButton" @click="signIn()">ログイン</button>
    </div>
    
    <SignInAlertModal name="InformationAlertModal"
                title="お知らせ"
                @done="toMypage()">
      <template slot="content">
        <ul>
        <li>
          お知らせがあります
        </li>
        </ul>
      </template>
    </SignInAlertModal>
  </div>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from 'vue-property-decorator';
  import {SignInChecker} from '@/validator/sign/SignInChecker';
  import {AuthenticationRequest} from '@/types/authentication/Authentication';
  import {tokenModule} from '@/stores/authentication/Authentication';
  import {loginModule} from '@/stores/authentication/Account';
  import SignGuideLine from '@/views/sign/SignGuideLine.vue';
  import AlertModal from '@/components/molecules/modal/AlertModal.vue';
  import api from '@/infrastructure/api/API';
  import SignInAlertModal from '@/views/sign/SignInAlertModal.vue';

  @Component({
    components: {
      SignInAlertModal,
      SignGuideLine,
    },
  })
  export default class SignIn extends Vue {
    // data
    request: AuthenticationRequest = {
      accessId: '',
      password: '',
    };
    messages: string[] = [];

    // props
    @Prop({default: ''})
    accessId!: string;

    // computed
    get hasError(): boolean {
      return this.messages.length > 0;
    }


    // methods
    async signIn(): Promise<void> {
      this.messages = [];

      const validator = new SignInChecker(this.request);
      if (validator.hasErrors) {
        this.messages = validator.getMessages;
        return;
      }

      await tokenModule.authenticate(this.request)
        .then(async (response: any) => {
          await this.getAccount();
          this.openAlertModal();
        })
        .catch((error: any) => {
          // 入力エラー
          if (error.data.errors) {
            this.messages = error.data.errors;
            return;
          }
          if (error.data.message) {
            this.messages = [error.data.message];
            return;
          }
        });

      if (!this.hasError) {
        await this.toMypage();
      }
    }

    async getAccount(): Promise<void> {
      await loginModule.getAccount();
    }


    openAlertModal(): void {
      //if (this.hasInformation || this.hasContact) {
      //  this.$modal.show('InformationAlertModal');
      //}
    }

    async toMypage(): Promise<void> {
      await this.getAccount();
      this.$router.push({name: 'mypage'});
    }

    validate(): void {
      this.request.accessId = this.request.accessId.replace(/[^A-Z\-0-9]/g, '');
    }

    // life cyclehook
    created(): void {
      this.request.accessId = this.accessId;
    }
  }
</script>

<style lang="scss" scoped>
  .accessId {
    ime-mode: inactive;
  }

  ul {
    display: -webkit-flex;
    display: flex;
    -webkit-justify-content: space-between;
    justify-content: space-between;
    -webkit-align-items: flex-start;
    align-items: flex-start;
    -webkit-flex-direction: column;
    flex-direction: column;
    height: 50px;
  }
</style>
