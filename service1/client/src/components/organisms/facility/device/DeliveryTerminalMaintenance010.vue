<template>
  <b-container id="delivery-terminal-maintenance-organism" class="mt-5" data-cy="">

      <Title title="配送端末情報メンテナンス" class="text-center"></Title><br>

      <b-row>
        <b-col cols="12">

            <!-- 配送端末情報新規登録 -->
            <div class="div-section">
                <Button class="text-center" variantName="success" buttonText="配送端末情報新規登録" @click="toPage('/deliveryTerminalMaintenance/register')"></Button>
            </div>

           <!-- 配送端末情報検索 -->
            <div class="div-section" id="factory-search">
                <TitleSmall titleSmall="配送端末情報検索" class="text-center"></TitleSmall>
                <b-row>
                    <b-col cols="9">

                         <FormSelect class="pl-4" name="factory-select"
                                labelCols="4" labelName="工場（部門コード）" inputClassName=""
                                v-model="searchHtDeviceRequest.facilityCode"
                                :options="facilityCodeList.facilityCodeList"
                                text-field='deliveryWorkplaceName'
                                value-field='facilityCode'></FormSelect>
                    </b-col>
                    <b-col cols="3">
                        <Button class="" variantName="info" buttonText="検索" @click="searchHtDevice()"></Button><br>
                    </b-col>

                </b-row>
                <div id="table-container" class="mt-3">
                    <div  class="mt-3" v-if="searchHtDeviceList.htDeviceList.length > 0">
                    <b-table hover
                             table-variant="info"
                             :bordered="true"
                             responsive
                             :items="searchHtDeviceList.htDeviceList"
                             :fields="hostDeviceFields"
                             selectable
                             @row-clicked="toDetailPage"
                             >
                        <template #cell(htHostName)="row">
                            <p>{{row.item.htHostName}}</p>
                        </template>
                        <template #cell(htHostIp)="row">
                            <p>{{row.item.htHostIp}}</p>
                        </template>
                        <template #cell(deliveryWorkplaceName)="row">
                            <p>{{row.item.deliveryWorkplaceName}}</p>
                        </template>
                        <template #cell(location)="row">
                            <p>{{row.item.location}}</p>
                        </template>
                        <template #head(htDeviceList)="row">
                            <b-col cols="12" class="text-center">ハンディ端末</b-col>
                            <b-row>
                            <b-col cols="4" >ハンディIP</b-col>
                            <b-col cols="4" >設置場所</b-col>
                            <b-col cols="4" >
                                種別<SortColumnIcon v-model="testSortOrder.order"
                                                  @input="changeSort(testSortOrder)"
                                                  data-cy="テストソート"></SortColumnIcon>
                            </b-col>
                            </b-row>

                        </template>

                        <template #cell(htDeviceList)="row">
                            <b-table
                                thead-class="d-none"
                                table-variant="light"
                                striped
                                :items="row.item.htDeviceList"
                                :fields="htDeviceListField"
                                responsive
                            ></b-table>
                        </template>
                    </b-table>
                </div>
                </div>
                <b-row>
                    <b-col offset="4" cols="4">
                    <pagination
                        :current-page="searchHtDeviceRequest.page"
                        :next-page="nextPage"
                        :per-page="searchHtDeviceRequest.perPage"
                        :previous-page="previousPage"
                        :total="total"
                        v-model="searchHtDeviceRequest.page"
                    ></pagination>
                    </b-col>
                    <b-row cols="2">
                    <PerPageInput v-model="searchHtDeviceRequest.perPage" :min-per-page="10"/>
                    件表示
                    </b-row>
                </b-row>
            </div>
        </b-col>
      </b-row>
  </b-container>
</template>

<script lang="ts">
import {Component, Prop, Watch, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import {progress} from '@/infrastructure/script/Progress';
import Title from '@/components/atoms/Title.vue';
import TitleSmall from '@/components/atoms/TitleSmall.vue';
import Button from '@/components/atoms/Button.vue';
import FormInputText from '@/components/atoms/FormInputText.vue';
import FormSelect from '@/components/atoms/FormSelect.vue';
import Form from '@/components/molecules/Form.vue';
import api from '@/infrastructure/api/API';
import {EmptyBumonCode, BumonCode} from '@/types/htDevice/BumonCode';
import {EmptySlipType, SlipType} from '@/types/htDevice/SlipType';
import {EmptyHtDeviceGetResponse, HtDeviceGetRequest, HtDeviceGetResponse} from '@/types/htDevice/htDeviceGet';
import Pagination from '@/components/atoms/Pagination.vue';
import PerPageSelector from '@/components/molecules/selector/PerPagesSelector.vue';
import PerPageInput from '@/components/atoms/PerPageInput.vue';
import SortColumnIcon from '@/components/atoms/SortColumnIcon.vue';
import {SortOrder, SortOrderRequest} from '@/types/sort/SortOrder';

@Component({
  components: {
      Title,
      TitleSmall,
      Button,
      FormInputText,
      FormSelect,
      Pagination,
      PerPageSelector,
      SortColumnIcon,
      PerPageInput,
  },
})
export default class DeliveryTerminalMaintenance010 extends Vue {

  @Prop()
  facilityCodeList: BumonCode | undefined;
    testSortOrder: SortOrderRequest = {
        sort: 'test',
        order: SortOrder.NONE,
    };
  // data
  sample = [];
  inputValue = '';
  facilityCodeSearchInput = '';
  total: number = 0;
  nextPage: number | null = null;
  previousPage: number | null = null;

  searchHtDeviceRequest: HtDeviceGetRequest = {
    facilityCode: null,
    page: 1,
    perPage: 10,
    sorts: [this.testSortOrder],
  };
  searchHtDeviceList: HtDeviceGetResponse = {...EmptyHtDeviceGetResponse};

  hostDeviceFields = [
      {key: 'htHostName', label: '端末ホスト名'},
      {key: 'htHostIp', label: 'IPアドレス'},
      {key: 'deliveryWorkplaceName', label: '工場(部門コード)'},
      {key: 'location', label: '設置場所'},
      {key: 'htDeviceList', label: 'ハンディ端末'},
  ];

  htDeviceListField = [
      {key: 'htDeviceIp', label: 'ハンディ端末IPアドレス'},
      {key: 'location', label: 'ハンディ端末設置場所'},
      {key: 'slipType', label: '伝票種別'},
  ];

  // computed

    // watch
    @Watch('searchHtDeviceRequest.page')
    onChangePage() {
        this.searchHtDevice();
    }
    // watch
    @Watch('searchHtDeviceRequest.perPage')
    onChangePerPage() {
        this.searchHtDeviceRequest.page = 1;
        this.searchHtDevice();
    }

  // method
  async initialize(): Promise<void> {
      await this.searchHtDevice();
  }

  async searchHtDevice(): Promise<void> {
        const getHtDeviceList = async (): Promise<void> => {
            await api.getHtDevice(this.searchHtDeviceRequest)
                .then((response: any) => {
                    this.searchHtDeviceList = response;
                    this.nextPage = this.searchHtDeviceList.page.nextPage;
                    this.previousPage = this.searchHtDeviceList.page.previousPage;
                    this.total = this.searchHtDeviceList.page.resultCount;
                });
        };
        await progress(getHtDeviceList);
    }

  toDetailPage(row: { htHostId: string; }) {
    const path = '/deliveryTerminalMaintenance/details/' + row.htHostId;
    this.toPage(path);
  }
  toPage(page: string) {
      this.$router.push(page);
  }

  changeSort(request: SortOrderRequest): void {

      this.searchHtDeviceRequest.sorts
          .filter((sort) => sort.sort !== request.sort)
          .forEach((sort) => sort.order = SortOrder.NONE);
      if (request.order !== SortOrder.NONE) {
          this.searchHtDeviceRequest.sorts = [request];
      } else {
          this.searchHtDeviceRequest.sorts = [];
      }
      this.searchHtDevice();
  }

  // lifecycle hooks
  created(): void {
      this.searchHtDevice();
  }
}
</script>
