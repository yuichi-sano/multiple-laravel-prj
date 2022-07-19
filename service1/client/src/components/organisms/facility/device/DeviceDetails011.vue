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
                                 maxLength="" v-model="updateDeviceRequest.htHostName" placeholder=""
                  >
                  </FormInputText>
                  <FormInputText class="ml-5" name="ip-address-input" labelCols="4"
                                 labelName="IPアドレス" inputClassName="" type="text" maxLength=""
                                 v-model="updateDeviceRequest.htHostIp" placeholder=""
                  >
                  </FormInputText>
                  <FormSelect class="ml-5" name="factory-select"
                              labelCols="4" labelName="工場（部門コード）"
                              inputClassName=""
                              v-model="updateDeviceRequest.facilityCode"
                              :options="workplace"
                              text-field="deliveryWorkPlaceName"
                              value-field="facilityCode">
                  </FormSelect>
                  <FormInputText class="ml-5" name="location-input" labelCols="4"
                                 labelName="設置場所" inputClassName="" type="text"
                                 maxLength="" v-model="updateDeviceRequest.location" placeholder=""
                  >
                  </FormInputText>
                </div>
              </b-col>
            </b-row>
            <b-row>
              <b-col cols="11">
                <div class="mt-5">
                  <div class="form-label ml-5 handy-terminal-label font-weight-bold">
                    端末情報
                  </div>
                </div>
              </b-col>
            </b-row>
            <b-row v-for="(device, deviceRowIndex) in deliveryDevices" :key="deviceRowIndex">
              <b-col cols="11">
                <div class="mt-3">
                  <FormInputText class="ml-5" id="handy-terminal-IPaddress-input-1" name="handy-terminal-IPaddress-input1"
                                 labelCols="4" labelName="端末IPアドレス" inputClassName=""
                                 type="text" maxLength=""
                                 v-model="device.DeviceIp" placeholder="">

                  </FormInputText>
                  <FormInputText class="ml-5" id="handy-terminal-location-input-1" name="handy-terminal-location-input1"
                                 labelCols="4" labelName="端末設置場所" inputClassName=""
                                 type="text" maxLength=""
                                 v-model="device.location" placeholder="">
                  </FormInputText>
                </div>
                <b-row>
                  <b-col>
                    <div class="text-right">
                      <b-icon id="add-handy-terminal-icon" class="h4 icons"
                              icon="plus-circle-fill" variant="info"
                              @click="addDevice">
                      </b-icon>
                      <b-icon id="delete-handy-terminal-icon" class="h4 ml-2 icons"
                              icon="dash-circle-fill" variant="danger"
                              @click="deleteDevice(deviceRowIndex)"
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
        :detail-key-map="deviceField"
        :key-map-values="[
          {key: 'facilityCode', values: this.workplace, mapDefinition: {id:'facilityCode',value:'deliveryWorkPlaceName'}}]"
        ref="deviceUpdateConfirm"
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
import {EmptyDeviceUpdateRequest, Device, DeviceUpdateRequest} from '@/types/device/DeviceUpdate';
import {EmptyWorkPlace, WorkPlace} from '@/types/device/WorkPlace';
import {SlipType} from '@/types/device/SlipType';
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
export default class DeviceDetails011 extends Vue {

  @Prop({type: Array})
  workplace: Array<{
    facilityCode: '';
    deliveryWorkPlaceName: '';
  }> | undefined;
  @Prop({type: String})
  detailId: string | undefined;

  // data
  inputValue = '';
  selected = '';
  apiError: any = null;
  successModalId: string = 'updateDeviceSuccess';
  errorModalId: string = 'updateDeviceError';
  deliveryDevices: Device[] = [
    {
      DeviceTmpId : 1,
      DeviceIp : '',
      location :  '',
    },
  ];

  deviceField = [
    {key: 'htHostName', label: '配送端末ホスト名'},
    {key: 'htHostIp', label: 'IPアドレス'},
    {key: 'facilityCode', label: '工場'},
    {key: 'location', label: '設置場所'},
    {key: 'DeviceIp', label: '端末IPアドレス'},
  ];
  updateDeviceRequest: DeviceUpdateRequest = {...EmptyDeviceUpdateRequest};

  // computed
  // method
  async initialize(): Promise<void> {
    await this.getDeviceDetails();
  }

  addDevice() {
    // @ts-ignore
    const maxTempId = Math.max.apply(null, this.deliveryDevices.map(function(device) {
      return device.DeviceTmpId;
    }));
    this.deliveryDevices.push(
      {
        DeviceTmpId : maxTempId + 1,
        DeviceIp: '',
        location: '',
      },
    );
  }
  setDevice(list: Device[]) {
    const Devices = this.deliveryDevices;
    list.forEach((unit) => {
      // @ts-ignore
      const maxTempId = Math.max.apply(null, this.deliveryDevices.map(function(device) {
        return device.DeviceTmpId;
      }));
      Devices.push(
        {
          DeviceTmpId : maxTempId + 1,
          DeviceIp: unit.DeviceIp,
          location: unit.location,
        },
      );
    });
    this.deleteDevice(0);
  }

  deleteDevice(idx: number) {
    if (this.deliveryDevices.length > 1) {
      this.deliveryDevices.splice(idx, 1);
    } else {
      alert('ホスト機には必ず1つ以上の端末機が必要です');
    }
  }
  async getDeviceDetails(): Promise<void> {

    const getDeviceDetail  = async (): Promise<void> => {
      await api.getDeviceDetails(Number(this.detailId))
        .then((response: any) => {
          this.updateDeviceRequest = {...response};
          if (this.updateDeviceRequest.DeviceList.length > 0) {
            this.setDevice(this.updateDeviceRequest.DeviceList);
          }
        }).catch((error: any): void => {
          if (error.status === 400) {
            this.apiError = error;
            this.$bvModal.show(this.errorModalId);
          }
        },
      );
    };
    await progress(getDeviceDetail);
  }

  updateConfirm() {
    this.updateDeviceRequest.DeviceList = this.deliveryDevices;
    // @ts-ignore
    this.$refs.deviceUpdateConfirm.showModal(this.updateDeviceRequest);
  }
  async executeUpdate(data: any): Promise<void> {
    const updateDevice =  async (): Promise<void> => {
      this.updateDeviceRequest = {...data};
      await api.updateDevice(this.updateDeviceRequest, Number(this.detailId))
        .then((response: any) => {
          this.$bvModal.show(this.successModalId);
        }).catch((error: any): void => {
          if (error.status === 400) {
            this.apiError = error;
            this.$bvModal.show(this.errorModalId);

          }
        });
    };
    await progress(updateDevice);
  }

  async bindConfirm(confirmed: boolean, data: any): Promise<void> {
    if (confirmed) {
      await this.executeUpdate(data);
    }
    // @ts-ignore
    this.$refs.deviceUpdateConfirm.hideModal();
  }
  async afterUpdate() {
    this.updateDeviceRequest = {...EmptyDeviceUpdateRequest};
    location.reload();
  }
  async errorClose() {
    // this.updateDeviceRequest = {...EmptyDeviceUpdateRequest}
  }
  toBack() {
    this.$router.push('/deviceMaintenance');
  }

  // lifecycle hooks
  async created(): Promise<void> {
    await this.initialize();
  }


}
</script>
