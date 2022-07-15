<template>
  <div id="login"
       data-cy="パスワード入力画面">


    <b-container id="login-organism" class="mt-5" data-cy="">
      <Title title="マスタメンテナンス業務システム" class="text-center"></Title><br>
      <b-row>
        <b-col cols="6" offset="2">
          <ApiError :api-error="apiError"></ApiError>

          <div v-show="hasError">
            <ul id="messages">
              <li v-for="message in messages" :key="message" class="text-danger">{{message}}</li>
            </ul>
          </div>
          <Form :form-data="request">
            <FormInputText name="access-id" labelCols="3" labelName="アクセスID" inputClassName="w-30" type="text" maxLength="40"
                           v-model="request.userId" placeholder="">

            </FormInputText>
            <FormInputPassword name="password" :labelCols="3" labelName="パスワード" inputClassName="w-30"
                               v-model="request.password"></FormInputPassword>
            <Button class="text-center mt-4" variantName="success" buttonText="ログイン" @click="signIn()"></Button>
          </Form>

        </b-col>
      </b-row>
    </b-container>
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
import Form from '@/components/molecules/Form.vue';
import FormInputText from '@/components/atoms/FormInputText.vue';
import FormInputPassword from '@/components/atoms/FormInputPassword.vue';
import Button from '@/components/atoms/Button.vue';
import ApiError from '@/components/molecules/ApiError.vue';

@Component({
  components: {
    SignInAlertModal,
    SignGuideLine,
    Form,
    FormInputText,
    FormInputPassword,
    Button,
    ApiError,
  },
})
export default class SignIn extends Vue {
  // data
  request: AuthenticationRequest = {
    userId: '',
    password: '',
  };
  messages: string[] = [];
  apiError: any = null;

  // props
  @Prop({default: ''})
  userId!: string;

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
        // this.openAlertModal();
        await this.toMenu();
      })
      .catch((error: any) => {
        // 入力エラー
        this.apiError = error;
        if (error.data.errors) {
          this.messages = error.data.errors;
          return;
        }
        if (error.data.message) {
          this.messages = [error.data.message];
          return;
        }
      });

  }

  async getAccount(): Promise<void> {
    // await loginModule.getAccount();
  }


  openAlertModal(): void {
    // if (this.hasInformation || this.hasContact) {
    //  this.$modal.show('InformationAlertModal');
    // }
  }

  async toMenu(): Promise<void> {
    await this.getAccount();
    await this.$router.push({name: 'menu'});
  }

  validate(): void {
    this.request.userId = this.request.userId.replace(/[^A-Z\-0-9]/g, '');
  }

  // life cyclehook
  created(): void {
    this.request.userId = this.userId;
  }
}
</script>

<style lang="scss" scoped>
.userId {
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
