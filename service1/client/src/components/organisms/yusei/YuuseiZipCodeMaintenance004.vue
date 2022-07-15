<template>
  <b-container id="yuusei-zipcode-maintenance-organism" class="mt-5" data-cy="">

    <Title title="郵政郵便番号マスタメンテナンス" class="text-center"></Title><br>
    <b-row>
      <b-col>
      </b-col>
      <b-col cols="9">
        <ApiError :api-error="apiError"></ApiError>
        <div class="search-link mb-2">
          <a href="#zip-code-search">郵便番号検索一覧へ</a>
        </div>
        <!-- 郵政郵便番号情報取得 -->
        <div class="div-section">
          <b-row>
            <b-col sm="3">
              <div class="form-label label-update">
                前回更新日時 :
              </div>
              <div class="form-label label-update-user">
                前回更新者 :
              </div>
            </b-col>
            <div class="col-4">
              <div class="form-label label-update">
                <span id="last-update-time">{{updateHistory.bulkUpdateDate}}</span>
              </div>
              <div class="form-label label-user-update">
                <span id="last-update-user">{{updateHistory.bulkUser}}</span>さん
              </div>
            </div>
          </b-row>
          <Button class="text-center mt-4" variantName="info" buttonText="郵政郵便番号取得" @click="getConfirm()"></Button>
          <!-- 郵政郵便番号情報更新 -->
          <div id="update-area-container" class="mt-3" v-if="diffList.differenceNumber > 0">
            <div class="">
              <p><span id="new-data-count">{{diffList.differenceNumber}}</span>件のレコード更新が検出されました。</p>
            </div>

            <div v-if="diffList.differenceNumber < 200">
              zipsテーブル更新差分
              <b-table striped hover
                       responsive
                       :items="diffList.zipCodeList"
                       :fields="diffFields"
              ></b-table>
            </div>
            <div v-else>
                更新差分が大きすぎるため表示しきれません、データマイグレーションSQLを直接比較しご確認ください
            </div>

            <div v-if="diffList.differenceNumber < 200" class="my-3">
              yuseiyubinbangousテーブル更新差分
              <b-table striped hover
                       responsive
                       :items="diffList.yuseiList"
                       :fields="diffFields"
              ></b-table>
            </div>
            <div v-else>
              更新差分が大きすぎるため表示しきれません、データマイグレーションSQLを直接比較しご確認ください
            </div>
            <Button class="text-center mt-4" variantName="success" buttonText="郵政郵便番号更新" @click="migrationConfirm()"></Button>
          </div>
          <div  class="mt-3" v-else-if="diffList.differenceNumber === 0">
            更新データを取得しましたが、前回更新時との差分はありませんでした。
            <Button class="text-center mt-4" variantName="success" buttonText="郵政郵便番号更新" @click="migrationConfirm()"></Button>
          </div>
        </div>

        <!-- 郵政郵便番号個別登録 -->
        <div class="div-section">
          <TitleSmall titleSmall="郵政郵便番号個別登録" class="text-center"></TitleSmall>

          <b-row>
            <div class="col-3">
              <div class="form-label label-register">
                直近更新日時 :
              </div>
              <div class="form-label label-update-user">
                直近更新者 :
              </div>
            </div>
            <div class="col-4">
              <div class="form-label label-update">
                <span >{{updateHistory.addDate}}</span>
              </div>
              <div class="form-label label-user-update">
                <span >{{updateHistory.user}}</span>さん
              </div>
            </div>
          </b-row>
          <div id="register-area-container" class="mt-3">
            <div class="form-label label-updated-data font-weight-bold">
              直近に更新された情報 :
            </div>
            <div class="updated-data">
              <b-table striped hover
                       :items="[updateHistory]"
                       :fields="zipHistoryFields"
              ></b-table>
            </div>
            <Button v-b-modal.modal-zipcode-register class="text-center mt-4" variantName="success" buttonText="郵政郵便番号個別登録" @click=""></Button>
          </div>
        </div>

        <!-- 郵政郵便番号個別登録モーダル -->
        <!-- <YuuseiZipCodeRegister005> -->
        <b-modal id="modal-zipcode-register"
                 title="郵政郵便番号個別登録" header-bg-variant="light"
                 ok-title="登録" ok-variant="success"
                 @ok="registerConfirm"
                 cancel-title="閉じる" footer-class="text-center"
        >
          <div class="my-5 mx-4">
            <FormInputText class="" name="zip-code-register-input" labelCols="4" labelName="郵便番号" inputClassName="" type="text" maxLength="" v-model="addZipRequest.zipCode" placeholder=""></FormInputText><br>
            <FormSelect class="" name="prefecture-select"
                        labelCols="4" labelName="都道府県" inputClassName=""
                        v-model="addZipRequest.kenCode"
                        :options="prefectureList"
                        text-field='prefectureName'
                        value-field='prefectureId'
            >
            </FormSelect>
            <FormInputText class="" name="prefecture-input-kana" labelCols="4" labelName="都道府県(カナ)" inputClassName="" type="text" maxLength="" v-model="addZipRequest.kenmeiKana" placeholder=""></FormInputText>
            <FormInputText class="" name="municipality-input" labelCols="4" labelName="市区町村" inputClassName="" type="text" maxLength="" v-model="addZipRequest.sikumei" placeholder=""></FormInputText>
            <FormInputText class="" name="municipality-input-kana" labelCols="4" labelName="市区町村(カナ)" inputClassName="" type="text" maxLength="" v-model="addZipRequest.sikumeiKana" placeholder=""></FormInputText>
            <FormInputText class="" name="municipality-code-input" labelCols="4" labelName="市区町村コード" inputClassName="" type="text" maxLength="" v-model="addZipRequest.sikuCode" placeholder=""></FormInputText><br>
            <FormInputText class="" name="district-input" labelCols="4" labelName="町域" inputClassName="" type="text" maxLength="" v-model="addZipRequest.town" placeholder=""></FormInputText>
            <FormInputText class="" name="district-input-kana" labelCols="4" labelName="町域(カナ)" inputClassName="" type="text" maxLength="" v-model="addZipRequest.townKana" placeholder=""></FormInputText>
          </div>

        </b-modal>
        <!-- </YuuseiZipCodeRegister005> -->

        <!-- 郵政郵便番号検索 -->
        <div class="div-section" id="zip-code-search">
          <TitleSmall titleSmall="郵政郵便番号検索" class="text-center"></TitleSmall>
          <b-row>
            <b-col cols="9">
              <FormInputText class="pl-5" name="zip-code-search-input" labelCols="3" labelName="郵便番号" inputClassName="" type="text" maxLength="" v-model="searchZipRequest.zipCode" placeholder=""></FormInputText>
            </b-col>
            <b-col cols="3">
              <Button class="" variantName="info" buttonText="検索" @click="searchZip()"></Button><br>
            </b-col>

          </b-row>
          <div id="table-container" class="mt-3" v-if="searchZipList.zipCodeList.length > 0">
            <b-table striped hover
                     :items="searchZipList.zipCodeList"
                     :fields="zipListFields"
            >
              <template #cell(update)="row">
                <b-button size="sm" @click="updateConfirm(row)" class="mr-2" variant="warning">
                  更新
                </b-button>
                <b-button size="sm" @click="deleteConfirm(row)" class="mr-2" variant="danger">
                  削除
                </b-button>
              </template>
              <template #cell(zipCode)="row">
                <b-form-input v-model="row.item.zipCode" type="text"></b-form-input>
              </template>
              <template #cell(kenCode)="row">
                <b-form-input v-model="row.item.kenCode" type="text"></b-form-input>
              </template>
              <template #cell(sikumei)="row">
                <b-form-input v-model="row.item.sikumei" type="text"></b-form-input>
              </template>
              <template #cell(sikuCode)="row">
                <b-form-input v-model="row.item.sikuCode" type="text"></b-form-input>
              </template>

            </b-table>
          </div>
        </div>


      </b-col>
      <b-col></b-col>
      <ConfirmModal
        :bind-confirm="bindGetConfirm"
        :show-detail="false"
        ref="yuseiGetConfirm">
        <template #header>
          <h5 class="font-weight-bold">郵政郵便番号取得</h5>
        </template>
        <template #content>
          <div class="h6 font-weight-bold">郵政HPより最新データを取得しますがよろしいでしょうか？</div>
        </template>
      </ConfirmModal>
      <ConfirmModal
        :bind-confirm="bindMigrationConfirm"
        ref="yuseiMigrationConfirm">
        <template #header>
          <h5 class="font-weight-bold">郵政郵便番号データマイグレーション</h5>
        </template>
        <template #content>
          <div class="h6 font-weight-bold">最新データを適用しますが本当によろしいですか？</div>
        </template>
      </ConfirmModal>
      <ConfirmModal
        :bind-confirm="bindRegisterConfirm"
        :show-detail="true"
        :detail-key-map="zipHistoryFields"
        ref="yuseiRegisterConfirm">
        <template #header>
          <h5 class="font-weight-bold">郵政郵便番号登録</h5>
        </template>
        <template #content>
          <div class="h6 font-weight-bold">下記内容が入力されています。本当に更新しますか?</div>
        </template>
      </ConfirmModal>
      <ConfirmModal
        :bind-confirm="bindDeleteConfirm"
        :show-detail="false"
        :detail-key-map="zipHistoryFields"
        ref="yuseiDeleteConfirm">
        <template #header>
          <h5 class="font-weight-bold">郵政郵便番号削除</h5>
        </template>
        <template #content>
          <div class="h6 font-weight-bold">下記郵便番号情報を削除します。本当によろしいですか?</div>
        </template>
      </ConfirmModal>
      <ConfirmModal
        :bind-confirm="bindConfirm"
        :show-detail="true"
        :detail-key-map="zipHistoryFields"
        ref="yuuseiUpdateConfirm">
        <template #header>
          <h5 class="font-weight-bold">郵政郵便番号更新</h5>
        </template>
        <template #content>
          <div class="h6 font-weight-bold">下記内容が入力されています。本当に更新しますか?</div>
        </template>
      </ConfirmModal>
      <SuccessInfoModal
        :success-modal-id="successModalIdForGet"
        :bind-close="afterGet"
      >
      </SuccessInfoModal>
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
import {progress} from '@/infrastructure/script/Progress';
import Title from '@/components/atoms/Title.vue';
import TitleSmall from '@/components/atoms/TitleSmall.vue';
import Button from '@/components/atoms/Button.vue';
import FormInputText from '@/components/atoms/FormInputText.vue';
import FormSelect from '@/components/atoms/FormSelect.vue';
import Form from '@/components/molecules/Form.vue';
import api from '@/infrastructure/api/API';
import {ZipCodeYuseiUpdate} from '@/types/zipCodeYusei/ZipCodeYuseiUpdate';
import {EmptyZipCodeKenAll, ZipCodeKenAll} from '@/types/zipCodeYusei/ZipCodeKenAll';
import {
  EmptyZipCodeYuseiSearchResponse,
  ZipCodeYuseiSearchRequest,
  ZipCodeYuseiSearchResponse,
} from '@/types/zipCodeYusei/ZipCodeYuseiSearch';
import {
  EmptyZipCodeIndividualRegisterRequest,
  EmptyZipCodeIndividualRegisterResponse,
  ZipCodeIndividualRegisterRequest,
} from '@/types/zipCodeYusei/ZipCodeIndividualRegister';
import {Prefecture} from '@/types/prefecture/Prefecture';
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
    Form,
    ApiError,
    ConfirmModal,
    SuccessInfoModal,
    ErrorInfoModal,
  },
})
export default class YuuseiZipCodeMaintenance004 extends Vue {
  // data
  @Prop()
  updateHistory: ZipCodeYuseiUpdate | undefined;
  @Prop()
  prefectureList: Prefecture[] | undefined;

  diffList: ZipCodeKenAll = {...EmptyZipCodeKenAll};
  sample = [];
  options = [];
  zipCodeSearchInput = '';

  zipCode = '';
  prefecture = '';
  prefectureKana = '';
  prefectureCode = '';
  municipality = '';
  municipalityKana = '';
  municipalityCode = '';
  district = '';
  districtKana = '';
  diffFields = [
    {key: 'old', label: '前回データ'},
    {key: 'new', label: '今回取得データ'},
  ];
  zipHistoryFields = [
    {key: 'zipCode', label: '郵便番号'},
    {key: 'kenmei', label: '県名'},
    {key: 'sikumei', label: '市区名'},
    {key: 'sikumei', label: '市区名'},
    {key: 'sikuCode', label: 'JISコード'},
  ];
  zipListFields = [
    {key: 'id', label: '郵便番号ID'},
    {key: 'zipCode', label: '郵便番号'},
    {key: 'kenCode', label: '県コード'},
    {key: 'sikumei', label: '市区名'},
    {key: 'sikuCode', label: 'JISコード'},
    {key: 'update', label: '更新'},
  ];
  searchZipRequest: ZipCodeYuseiSearchRequest = {zipCode: ''};
  searchZipList: ZipCodeYuseiSearchResponse = {...EmptyZipCodeYuseiSearchResponse};
  addZipRequest: ZipCodeIndividualRegisterRequest = {...EmptyZipCodeIndividualRegisterRequest};
  request: ZipCodeIndividualRegisterRequest = {...EmptyZipCodeIndividualRegisterRequest};
  apiError: any = null;
  successModalIdForGet: string = 'getYuseiSuccess';
  successModalId: string = 'updateYuuseiSuccess';
  errorModalId: string = 'updateYuuseiError';
  // computed

//   get isReadOnly(): boolean {
//     return loginModule.isReadOnly;
//   }

  // method
  async initialize(): Promise<void> {
    // await this.getSample();
  }

  getConfirm() {
    // @ts-ignore
    this.$refs.yuseiGetConfirm.showModal();
  }
  async bindGetConfirm(confirmed: boolean, data: any): Promise<void> {
    if (confirmed) {
      await this.executeGetKenAll();
    }
    // @ts-ignore
    this.$refs.yuseiGetConfirm.hideModal();
  }

  async executeGetKenAll(): Promise<void> {
    const kenAll =  async (): Promise<void> => {
      await api.getZipCodeKenAll()
        .then((response: any) => {
          this.diffList = response;
          if (this.diffList.differenceNumber == 0) {
            this.$bvModal.show(this.successModalIdForGet);
          }
        });
    };
    await progress(kenAll);
  }


  migrationConfirm() {
    // @ts-ignore
    this.$refs.yuseiMigrationConfirm.showModal();
  }
  async bindMigrationConfirm(confirmed: boolean, data: any): Promise<void> {
    if (confirmed) {
      await this.applyMigration();
    }
    // @ts-ignore
    this.$refs.yuseiMigrationConfirm.hideModal();
  }

  async applyMigration(): Promise<void> {
    const migration =  async (): Promise<void> => {
      await api.updateZipCodeYuseiBulk()
        .then((response: any) => {
          this.$bvModal.show(this.successModalId);
        }).catch((error: any): void => {
          if (error.status === 400) {
            this.apiError = error;
            this.$bvModal.show(this.errorModalId);
          }
        });
    };
    await progress(migration);
  }

  async searchZip(): Promise<void> {
    const searchZip =  async (): Promise<void> => {
      await api.getZipCodeYuseiSearch(this.searchZipRequest)
        .then((response: any) => {
          this.searchZipList = response;
        }).catch((error: any): void => {
          if (error.status === 400) {
            this.apiError = error;
            return;
          }
        });
    };
    await progress(searchZip);
  }
  async addZip(): Promise<void> {
    const sendZipCodeYusei =  async (): Promise<void> => {
      this.addZipRequest.kenmei = this.getKenmei(this.addZipRequest.kenCode);
      await api.sendZipCodeYusei(this.addZipRequest)
        .then((response: any) => {
          this.$bvModal.show(this.successModalId);
        }).catch((error: any): void => {
          if (error.status === 400) {
            this.apiError = error;
            this.$bvModal.show(this.errorModalId);
          }
        });
    };
    await progress(sendZipCodeYusei);
  }

  updateConfirm(row: any) {
    // @ts-ignore
    this.$refs.yuuseiUpdateConfirm.showModal(row.item);
  }
  async executeUpdate(data: any): Promise<void> {
    this.request = {...data};
    this.request.town = data.sikuika;
    this.request.townKana = data.sikuikaKana;
    const targetId = data.id;
    const upZipCodeYusei =  async (): Promise<void> => {
      await api.updateZipCodeYusei(this.request, targetId)
        .then((response: any) => {
          this.$bvModal.show(this.successModalId);
        }).catch((error: any): void => {
          if (error.status === 400) {
            this.apiError = error;
            this.$bvModal.show(this.errorModalId);
          }
        });
    };
    await progress(upZipCodeYusei);
  }

  registerConfirm() {
    this.addZipRequest.kenmei = this.getKenmei(this.addZipRequest.kenCode);
    // @ts-ignore
    this.$refs.yuseiRegisterConfirm.showModal(this.addZipRequest);
  }
  async bindRegisterConfirm(confirmed: boolean, data: any): Promise<void> {
    if (confirmed) {
      await this.addZip();
    }
    // @ts-ignore
    this.$refs.yuseiRegisterConfirm.hideModal();
  }

  async bindConfirm(confirmed: boolean, data: any): Promise<void> {
    if (confirmed) {
      await this.executeUpdate(data);
    }
    // @ts-ignore
    this.$refs.yuuseiUpdateConfirm.hideModal();
  }
  async afterGet() {

  }
  async afterUpdate() {
    this.request = {...EmptyZipCodeIndividualRegisterRequest};
    location.reload();
  }
  async errorClose() {
    this.request = {...EmptyZipCodeIndividualRegisterRequest};
  }


  deleteConfirm(row: any) {
    // @ts-ignore
    this.$refs.yuseiDeleteConfirm.showModal(row.item);
  }
  async bindDeleteConfirm(confirmed: boolean, data: any): Promise<void> {
    if (confirmed) {
      await this.deleteZip(data);
    }
    // @ts-ignore
    this.$refs.yuseiDeleteConfirm.hideModal();
  }
  async deleteZip(zipRow: { item: { id: number; }; }): Promise<void> {
    const deleteZipCodeYusei =  async (): Promise<void> => {
      await api.deleteZipCodeYusei(zipRow.item.id)
        .then((response: any) => {
          this.$bvModal.show(this.successModalId);
        }).catch((error: any): void => {
          if (error.status === 400) {
            this.apiError = error;
            this.$bvModal.show(this.errorModalId);
          }
        });
    };
    await progress(deleteZipCodeYusei);
  }
  getKenmei(kenCode: string | number): string {
    if (this.prefectureList === undefined) {
      return '';
    }
    const pref = this.prefectureList.find(
      (pref) => pref.prefectureId == kenCode,
    );
    // @ts-ignore
    return pref.prefectureName;
  }


  // lifecycle hooks
  created(): void {
    this.initialize();
  }
}
</script>
