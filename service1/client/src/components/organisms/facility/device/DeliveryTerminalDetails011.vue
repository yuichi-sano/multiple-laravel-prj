<template>
  <b-container id="delivery-terminal-details-organism" class="mt-5" data-cy="">

    <Title title="配送端末情報詳細" class="text-center"></Title><br>

    <b-row>
      <b-col cols="12">
        <!-- 佐川急便特殊店番詳細 -->
        <div class="div-section">
          <div id="input-container">
            <b-row>
              <b-col cols="11">
                <div class="">
                  <FormInputText class="ml-5" name="terminal-host-name-input"
                                 labelCols="4" labelName="配送端末ホスト名" inputClassName="" type="text"
                                 maxLength="" v-model="updateHtDeviceRequest.htHostName" placeholder=""
                  >
                  </FormInputText>
                  <FormInputText class="ml-5" name="ip-address-input" labelCols="4"
                                 labelName="IPアドレス" inputClassName="" type="text" maxLength=""
                                 v-model="updateHtDeviceRequest.htHostIp" placeholder=""
                  >
                  </FormInputText>
                  <FormSelect class="ml-5" name="factory-select"
                              labelCols="4" labelName="工場（部門コード）"
                              inputClassName=""
                              v-model="updateHtDeviceRequest.facilityCode"
                              :options="bumon"
                              text-field="deliveryWorkplaceName"
                              value-field="facilityCode">
                  </FormSelect>
                  <FormInputText class="ml-5" name="location-input" labelCols="4"
                                 labelName="設置場所" inputClassName="" type="text"
                                 maxLength="" v-model="updateHtDeviceRequest.location" placeholder=""
                  >
                  </FormInputText>
                  <FormSelect class="ml-5" name="receipt-type-select"
                              labelCols="4" labelName="伝票種別"
                              inputClassName=""
                              v-model="updateHtDeviceRequest.slipType"
                              :options="slipType"
                              text-field="slipTypeName"
                              value-field="slipTypeId">
                  </FormSelect>
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
            <b-row v-for="(deliveryTerminal, deliveryTerminalRowIndex) in deliveryDevices" :key="deliveryTerminalRowIndex">
              <b-col cols="11">
                <div class="mt-3">
                  <FormInputText class="ml-5" id="handy-terminal-IPaddress-input-1" name="handy-terminal-IPaddress-input1"
                                 labelCols="4" labelName="ハンディ端末IPアドレス" inputClassName=""
                                 type="text" maxLength=""
                                 v-model="deliveryTerminal.htDeviceIp" placeholder="">

                  </FormInputText>
                  <FormInputText class="ml-5" id="handy-terminal-location-input-1" name="handy-terminal-location-input1"
                                 labelCols="4" labelName="ハンディ端末設置場所" inputClassName=""
                                 type="text" maxLength=""
                                 v-model="deliveryTerminal.location" placeholder="">
                  </FormInputText>
                </div>
                <b-row>
                  <b-col>
                    <div class="text-right">
                      <b-icon id="add-handy-terminal-icon" class="h4 icons"
                              icon="plus-circle-fill" variant="info"
                              @click="addDeliveryTerminal">
                      </b-icon>
                      <b-icon id="delete-handy-terminal-icon" class="h4 ml-2 icons"
                              icon="dash-circle-fill" variant="danger"
                              @click="deleteDeliveryTerminal(deliveryTerminalRowIndex)"
                      >
                      </b-icon>
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
              <Button class="text-left mt-4" variantName="success" buttonText="更新" @click="updateConfirm"></Button>
            </b-col>
            <b-col></b-col>
          </b-row>
        </div>
      </b-col>
      <ConfirmModal
        :bind-confirm="bindConfirm"
        :show-detail="true"
        :detail-key-map="deliveryTerminalField"
        :key-map-values="[
          {key:'slipType' ,values: this.slipType, mapDefinition: {id:'slipTypeId',value:'slipTypeName'}},
          {key: 'facilityCode', values: this.bumon, mapDefinition: {id:'facilityCode',value:'deliveryWorkplaceName'}}]"
        ref="deliveryTerminalUpdateConfirm"
      >
        <template #header>
          <h5 class="font-weight-bold">配送端末マスタ更新</h5>
        </template>
        <template #content>
          <div class="h6 font-weight-bold">下記内容が入力されています。本当に更新しますか?</div>
        </template>
      </ConfirmModal>
      <SuccessInfoModal
        :success-modal-id="successModalId"
        :bind-close="afterUpdate"
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
import {EmptyHtDeviceUpdateRequest, HtDevice, HtDeviceUpdateRequest} from '@/types/htDevice/htDeviceUpdate';
import {EmptyBumonCode, BumonCode} from '@/types/htDevice/BumonCode';
import {SlipType} from '@/types/htDevice/SlipType';
import {progress} from '@/infrastructure/script/Progress';
import ConfirmModal from '@/components/molecules/modal/ConfirmModal.vue';
import SuccessInfoModal from '@/components/molecules/modal/SuccessInfoModal.vue';
import ErrorInfoModal from '@/components/molecules/modal/ErrorInfoModal.vue';
import ApiError from '@/components/molecules/ApiError.vue';
import {AddressSearchKeyWord} from '@/types/zipCodeSagawa/ZipCodeSagawaRegister';

@Component({
  components: {
    Title,
    TitleSmall,
    Button,
    FormInputText,
    FormSelect,
    ConfirmModal,
    SuccessInfoModal,
    ErrorInfoModal,
    ApiError,
  },
})
export default class DeliveryTerminalDetails011 extends Vue {

  @Prop({type: Array})
  bumon: Array<{
    facilityCode: '';
    deliveryWorkplaceName: '';
  }> | undefined;
  @Prop({type: Array})
  slipType: Array<{
    slipTypeId: number;
    slipTypeName: string;
  }> | undefined;
  @Prop({type: String})
  detailId: string | undefined;

  // data
  inputValue = '';
  selected = '';
  apiError: any = null;
  successModalId: string = 'updateDeliveryTerminalSuccess';
  errorModalId: string = 'updateDeliveryTerminalError';
  deliveryDevices: HtDevice[] = [
    {
      htDeviceTmpId : 1,
      htDeviceIp : '',
      location :  '',
    },
  ];

  deliveryTerminalField = [
    {key: 'htHostName', label: '配送端末ホスト名'},
    {key: 'htHostIp', label: 'IPアドレス'},
    {key: 'facilityCode', label: '工場'},
    {key: 'location', label: '設置場所'},
    {key: 'slipType', label: '伝票種別'},
    {key: 'htDeviceIp', label: 'ハンディ端末IPアドレス'},
  ];
  updateHtDeviceRequest: HtDeviceUpdateRequest = {...EmptyHtDeviceUpdateRequest};

  // computed
  // method
  async initialize(): Promise<void> {
    await this.getHtDeviceDetails();
  }

  addDeliveryTerminal() {
    // @ts-ignore
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
  setDeliveryTerminal(list: HtDevice[]) {
    const htDevices = this.deliveryDevices;
    list.forEach((unit) => {
      // @ts-ignore
      const maxTempId = Math.max.apply(null, this.deliveryDevices.map(function(device) {
        return device.htDeviceTmpId;
      }));
      htDevices.push(
        {
          htDeviceTmpId : maxTempId + 1,
          htDeviceIp: unit.htDeviceIp,
          location: unit.location,
        },
      );
    });
    this.deleteDeliveryTerminal(0);
  }

  deleteDeliveryTerminal(idx: number) {
    if (this.deliveryDevices.length > 1) {
      this.deliveryDevices.splice(idx, 1);
    } else {
      alert('ホスト機には必ず1つ以上のハンディ端末機が必要です');
    }
  }
  async getHtDeviceDetails(): Promise<void> {

    const getHtDeviceDetail  = async (): Promise<void> => {
      await api.getHtDeviceDetails(Number(this.detailId))
        .then((response: any) => {
          this.updateHtDeviceRequest = {...response};
          if (this.updateHtDeviceRequest.htDeviceList.length > 0) {
            this.setDeliveryTerminal(this.updateHtDeviceRequest.htDeviceList);
            // @ts-ignore @FIXME
            const slip = this.slipType.find((unit) => unit.slipTypeName === response.htDeviceList[0].slipType);
            // @ts-ignore @FIXME
            this.updateHtDeviceRequest.slipType = slip.slipTypeId;
          }
        }).catch((error: any): void => {
          if (error.status === 400) {
            this.apiError = error;
            this.$bvModal.show(this.errorModalId);
          }
        },
      );
    };
    await progress(getHtDeviceDetail);
  }

  updateConfirm() {
    this.updateHtDeviceRequest.htDeviceList = this.deliveryDevices;
    // @ts-ignore
    this.$refs.deliveryTerminalUpdateConfirm.showModal(this.updateHtDeviceRequest);
  }
  async executeUpdate(data: any): Promise<void> {
    const updateHtDevice =  async (): Promise<void> => {
      this.updateHtDeviceRequest = {...data};
      await api.updateHtDevice(this.updateHtDeviceRequest, Number(this.detailId))
        .then((response: any) => {
          this.$bvModal.show(this.successModalId);
        }).catch((error: any): void => {
          if (error.status === 400) {
            this.apiError = error;
            this.$bvModal.show(this.errorModalId);

          }
        });
    };
    await progress(updateHtDevice);
  }

  async bindConfirm(confirmed: boolean, data: any): Promise<void> {
    if (confirmed) {
      await this.executeUpdate(data);
    }
    // @ts-ignore
    this.$refs.deliveryTerminalUpdateConfirm.hideModal();
  }
  async afterUpdate() {
    this.updateHtDeviceRequest = {...EmptyHtDeviceUpdateRequest};
    location.reload();
  }
  async errorClose() {
    // this.updateHtDeviceRequest = {...EmptyHtDeviceUpdateRequest}
  }
  toBack() {
    this.$router.push('/deliveryTerminalMaintenance');
  }

  // lifecycle hooks
  async created(): Promise<void> {
    await this.initialize();
  }


}
</script>
