<template>
  <b-container id="device-register-organism" class="mt-5" data-cy="">

    <Title title="端末情報新規登録" class="text-center"></Title><br>

    <b-row>
      <b-col cols="12">
        <ApiError :api-error="apiError"></ApiError>

        <div class="div-section">
          <div id="input-container">
            <b-row>
              <b-col cols="11">
                <div class="">
                  <FormInputText class="ml-5" name="device-name-input" labelCols="4" labelName="端末名" inputClassName=""
                                 type="text" maxLength=""
                                 v-model="request.name" placeholder=""></FormInputText>
                  <FormInputText class="ml-5" name="ip-address-input" labelCols="4" labelName="IPアドレス" inputClassName=""
                                 type="text" maxLength=""
                                 v-model="request.ip" placeholder=""></FormInputText>
                  <FormSelect class="ml-5" name="workplace-select"
                              labelCols="4" labelName="拠点"
                              inputClassName=""
                              v-model="request.workplaceId"
                              :options="workplaceList"
                              text-field="workplaceName"
                              value-field="workplaceId"></FormSelect>
                </div>
              </b-col>
            </b-row>
          </div>
          <b-row>
            <b-col></b-col>
            <b-col>
              <Button class="text-right mt-4" variantName="secondary" buttonText="戻る" @click="toBack()"></Button>
            </b-col>
            <b-col>
              <Button class="text-left mt-4" variantName="success" buttonText="登録" @click="registerConfirm()"></Button>
            </b-col>
            <b-col></b-col>
          </b-row>
        </div>

        <ConfirmModal
          :bind-confirm="bindConfirm"
          :show-detail="false"
          ref="deviceRegisterConfirm">
          <template #header>
            <h5 class="font-weight-bold">端末情報新規登録`</h5>
          </template>
          <template #content>
            <div class="h6 font-weight-bold">登録しますか?</div>
          </template>
        </ConfirmModal>
        <SuccessInfoModal
          :success-modal-id="successModalId"
          :bind-close="afterRegister"
        >
        </SuccessInfoModal>
        <ErrorInfoModal
          :error-modal-id="errorModalId"
          :bind-close="errorClose"
        >
          <template #error>
            <ApiError :api-error="apiError"></ApiError>
          </template>
        </ErrorInfoModal>

      </b-col>
    </b-row>
  </b-container>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import Title from '@/components/atoms/Title.vue';
import TitleSmall from '@/components/atoms/TitleSmall.vue';
import Button from '@/components/atoms/Button.vue';
import FormInputText from '@/components/atoms/FormInputText.vue';
import FormSelect from '@/components/atoms/FormSelect.vue';
import Form from '@/components/molecules/Form.vue';
import api from '@/infrastructure/api/API';
import {EmptyDevicePostRequest, DevicePostRequest} from '@/types/device/DevicePost';
import {EmptyWorkplace, Workplace} from '@/types/device/Workplace';
import {progress} from '@/infrastructure/script/Progress';
import ApiError from '@/components/molecules/ApiError.vue';
import ConfirmModal from '@/components/molecules/modal/ConfirmModal.vue';
import SuccessInfoModal from '@/components/molecules/modal/SuccessInfoModal.vue';
import ErrorInfoModal from '@/components/molecules/modal/ErrorInfoModal.vue';

@Component({
  components: {
    Title,
    TitleSmall,
    Button,
    FormInputText,
    FormSelect,
    ApiError,
    ConfirmModal,
    SuccessInfoModal,
    ErrorInfoModal,
  },
})
export default class DeviceRegister012 extends Vue {

  // data
  @Prop()
  workplaceList: Array<Workplace> | undefined;
  apiError: any = null;
  successModalId: string = 'deviceRegisterSuccess';
  errorModalId: string = 'deviceRegisterError';
  request: DevicePostRequest = {...EmptyDevicePostRequest};


  // computed
  // method
  async initialize(): Promise<void> {
    // await this.getSample();
  }

  registerConfirm() {
    // @ts-ignore
    this.$refs.deviceRegisterConfirm.showModal(this.request);
  }

  async bindConfirm(confirmed: boolean, data: any): Promise<void> {
    // @ts-ignore
    this.$refs.deviceRegisterConfirm.hideModal();
  }
  async afterRegister() {
    this.request = {...EmptyDevicePostRequest};
    this.toBack();
    location.reload();
  }
  async errorClose() {
    // this.request = {...EmptyDevicePostRequest}
  }

  toBack() {
    this.$router.push('/deviceMaintenance');
  }


  // lifecycle hooks
  created(): void {
    this.initialize();
  }

}
</script>
