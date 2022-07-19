<template>
  <b-container id="device-details-organism" class="mt-5" data-cy="">

    <Title title="端末情報詳細" class="text-center"></Title><br>

    <b-row>
      <b-col cols="12">
        <!-- 佐川急便特殊店番詳細 -->
        <div class="div-section">
          <div id="input-container">
            <b-row>
              <b-col cols="11">
                <div class="">
                  <FormInputText class="ml-5" name="device-name-input"
                                 labelCols="4" labelName="端末名" inputClassName="" type="text"
                                 maxLength="" v-model="updateDeviceRequest.name" placeholder=""
                  >
                  </FormInputText>
                  <FormInputText class="ml-5" name="ip-address-input" labelCols="4"
                                 labelName="IPアドレス" inputClassName="" type="text" maxLength=""
                                 v-model="updateDeviceRequest.ip" placeholder=""
                  >
                  </FormInputText>
                  <FormSelect class="ml-5" name="workplace-select"
                              labelCols="4" labelName="拠点"
                              inputClassName=""
                              v-model="updateDeviceRequest.workplaceId"
                              :options="workplace"
                              text-field="workplaceName"
                              value-field="workplaceId">
                  </FormSelect>
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
          {key: 'workplaceId', values: this.workplace, mapDefinition: {id:'workplaceId',value:'workplaceName'}}]"
        ref="deviceUpdateConfirm"
      >
        <template #header>
          <h5 class="font-weight-bold">端末マスタ更新</h5>
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
import {EmptyWorkplace, Workplace} from '@/types/device/Workplace';
import {progress} from '@/infrastructure/script/Progress';
import ConfirmModal from '@/components/molecules/modal/ConfirmModal.vue';
import SuccessInfoModal from '@/components/molecules/modal/SuccessInfoModal.vue';
import ErrorInfoModal from '@/components/molecules/modal/ErrorInfoModal.vue';
import ApiError from '@/components/molecules/ApiError.vue';

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
    workplaceId: '';
    workplaceName: '';
  }> | undefined;
  @Prop({type: String})
  detailId: string | undefined;

  // data
  inputValue = '';
  selected = '';
  apiError: any = null;
  successModalId: string = 'updateDeviceSuccess';
  errorModalId: string = 'updateDeviceError';

  deviceField = [
    {key: 'name', label: '端末名'},
    {key: 'ip', label: 'IPアドレス'},
    {key: 'workplaceId', label: '拠点'},
  ];
  updateDeviceRequest: DeviceUpdateRequest = {...EmptyDeviceUpdateRequest};

  // computed
  // method
  async initialize(): Promise<void> {
    await this.getDeviceDetails();
  }


  async getDeviceDetails(): Promise<void> {

    const getDeviceDetail  = async (): Promise<void> => {
      await api.getDeviceDetails(Number(this.detailId))
        .then((response: any) => {
          this.updateDeviceRequest = {...response};
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
