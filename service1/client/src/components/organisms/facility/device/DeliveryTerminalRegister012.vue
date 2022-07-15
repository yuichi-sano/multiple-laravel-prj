<template>
  <b-container id="delivery-terminal-register-organism" class="mt-5" data-cy="">

    <Title title="配送端末情報新規登録" class="text-center"></Title><br>

    <b-row>
      <b-col cols="12">
        <ApiError :api-error="apiError"></ApiError>

        <div class="div-section">
          <div id="input-container">
            <b-row>
              <b-col cols="11">
                <div class="">
                  <FormInputText class="ml-5" name="terminal-host-name-input" labelCols="4" labelName="配送端末ホスト名" inputClassName=""
                                 type="text" maxLength=""
                                 v-model="request.htHostName" placeholder=""></FormInputText>
                  <FormInputText class="ml-5" name="ip-address-input" labelCols="4" labelName="IPアドレス" inputClassName=""
                                 type="text" maxLength=""
                                 v-model="request.htHostIp" placeholder=""></FormInputText>
                  <FormSelect class="ml-5" name="factory-select"
                              labelCols="4" labelName="工場（部門コード）"
                              inputClassName=""
                              v-model="request.facilityCode"
                              :options="bumon.facilityCodeList"
                              text-field="deliveryWorkplaceName"
                              value-field="facilityCode"></FormSelect>
                  <FormInputText class="ml-5" name="location-input" labelCols="4" labelName="設置場所" inputClassName=""
                                 type="text" maxLength=""
                                 v-model="request.location" placeholder=""></FormInputText>
                  <FormSelect class="ml-5" name="receipt-type-select"
                              labelCols="4" labelName="伝票種別"
                              inputClassName=""
                              v-model="request.slipType"
                              :options="slipType.slipTypeList"
                              text-field="slipTypeName"
                              value-field="slipTypeId"></FormSelect>
                </div>
              </b-col>
            </b-row>
            <b-row>
              <b-col cols="11">
                <div class="mt-5">
                  <div class="form-label ml-5 handy-terminal-label font-weight-bold">
                    ハンディ端末情報
                  </div>
                </div>
              </b-col>
            </b-row>
            <b-row v-for="(deliveryDevice, index) in deliveryDevices" :key="deliveryDevice.htDeviceTmpId">
              <b-col cols="11">
                <div class="mt-3">
                  <FormInputText class="ml-5"  name="handy-terminal-IPaddress-input" labelCols="4"
                                 labelName="ハンディ端末IPアドレス" inputClassName=""
                                 type="text" maxLength=""
                                 v-model="deliveryDevice.htDeviceIp"
                                 placeholder="192.168.21.21">

                  </FormInputText>
                  <FormInputText class="ml-5"  name="handy-terminal-location-input"
                                 labelCols="4" labelName="ハンディ端末設置場所"
                                 inputClassName="" type="text" maxLength=""
                                 v-model="deliveryDevice.location"
                                 placeholder="">

                  </FormInputText>
                </div>
                <b-row>
                  <b-col>
                    <div class="text-right">
                      <b-icon  class="h4 icons" icon="plus-circle-fill" variant="info" @click="addDeliveryTerminal"></b-icon>
                      <b-icon  class="h4 ml-2 icons" icon="dash-circle-fill" variant="danger" @click="deleteDeliveryTerminal(index)"></b-icon>
                    </div>
                  </b-col>
                </b-row>
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
          ref="deliveryTerminalRegisterConfirm">
          <template #header>
            <h5 class="font-weight-bold">配送端末情報新規登録`</h5>
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
import {EmptyHtDevicePostRequest, HtDevicePostRequest} from '@/types/htDevice/htDevicePost';
import {EmptyBumonCode, BumonCode} from '@/types/htDevice/BumonCode';
import {SlipType} from '@/types/htDevice/SlipType';
import {progress} from '@/infrastructure/script/Progress';
import ApiError from '@/components/molecules/ApiError.vue';
import {EmptyZipCodeSagawaRegisterRequest} from '@/types/zipCodeSagawa/ZipCodeSagawaRegister';
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
export default class DeliveryTerminalRegister012 extends Vue {

  // data
  @Prop()
  bumon: BumonCode | undefined;
  @Prop()
  slipType: SlipType | undefined;

  deliveryDevices = [
    {
      htDeviceTmpId : 1,
      htDeviceIp : '',
      location :  '',
    },
  ];
  apiError: any = null;
  successModalId: string = 'deliveryTerminalRegisterSuccess';
  errorModalId: string = 'deliveryTerminalRegisterError';
  request: HtDevicePostRequest = {...EmptyHtDevicePostRequest};


  // computed
  // method
  async initialize(): Promise<void> {
    // await this.getSample();
  }

  addDeliveryTerminal() {
    const maxTempId = Math.max.apply(null, this.deliveryDevices.map(function(device) {
      return device.htDeviceTmpId;
    }));
    this.deliveryDevices.push(
      {
        htDeviceTmpId : maxTempId + 1,
        htDeviceIp: '',
        location: '',
      },
    );
  }

  deleteDeliveryTerminal(idx: number) {
    if (this.deliveryDevices.length > 1) {
      this.deliveryDevices.splice(idx, 1);
    } else {
      alert('ホスト機には必ず1つ以上のハンディ端末機が必要です');
    }
  }

  async addHtDevice(): Promise<void> {
    const sendHtDevice =  async (): Promise<void> => {
      const request = this.request;
      // @ts-ignore
      request.htDeviceList = this.deliveryDevices;
      await api.sendHtDevice(request)
        .then((response: any) => {
          this.$bvModal.show(this.successModalId);
        }).catch((error: any): void => {
          if (error.status === 400) {
            this.apiError = error;
            this.$bvModal.show(this.errorModalId);
          }
        });
    };
    await progress(sendHtDevice);
  }

  registerConfirm() {
    // @ts-ignore
    this.$refs.deliveryTerminalRegisterConfirm.showModal(this.request);
  }

  async bindConfirm(confirmed: boolean, data: any): Promise<void> {
    if (confirmed) {
      await this.addHtDevice();
    }
    // @ts-ignore
    this.$refs.deliveryTerminalRegisterConfirm.hideModal();
  }
  async afterRegister() {
    this.request = {...EmptyHtDevicePostRequest};
    this.toBack();
    location.reload();
  }
  async errorClose() {
    // this.request = {...EmptyHtDevicePostRequest}
  }

  toBack() {
    this.$router.push('/deliveryTerminalMaintenance');
  }


  // lifecycle hooks
  created(): void {
    this.initialize();
  }

}
</script>
