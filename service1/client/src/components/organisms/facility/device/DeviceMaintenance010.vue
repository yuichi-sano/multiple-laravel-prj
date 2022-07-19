<template>
  <b-container id="delivery-terminal-maintenance-organism" class="mt-5" data-cy="">

      <Title title="配送端末情報メンテナンス" class="text-center"></Title><br>

      <b-row>
        <b-col cols="12">

            <!-- 配送端末情報新規登録 -->
            <div class="div-section">
                <Button class="text-center" variantName="success" buttonText="配送端末情報新規登録" @click="toPage('/deviceMaintenance/register')"></Button>
            </div>

           <!-- 配送端末情報検索 -->
            <div class="div-section" id="factory-search">
                <TitleSmall titleSmall="配送端末情報検索" class="text-center"></TitleSmall>
                <b-row>
                    <b-col cols="9">

                         <FormSelect class="pl-4" name="factory-select"
                                labelCols="4" labelName="工場（部門コード）" inputClassName=""
                                v-model="searchDeviceRequest.workPlaceId"
                                :options="workPlaceList"
                                text-field='workplaceName'
                                value-field='workPlaceId'></FormSelect>
                    </b-col>
                    <b-col cols="3">
                        <Button class="" variantName="info" buttonText="検索" @click="searchDevice()"></Button><br>
                    </b-col>

                </b-row>
                <div id="table-container" class="mt-3">
                    <div  class="mt-3" v-if="searchDeviceList.DeviceList.length > 0">
                    <b-table hover
                             table-variant="info"
                             :bordered="true"
                             responsive
                             :items="searchDeviceList.DeviceList"
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
                        <template #cell(deliveryWorkPlaceName)="row">
                            <p>{{row.item.deliveryWorkPlaceName}}</p>
                        </template>
                        <template #cell(location)="row">
                            <p>{{row.item.location}}</p>
                        </template>
                        <template #head(DeviceList)="row">
                            <b-col cols="12" class="text-center">端末</b-col>
                            <b-row>
                            <b-col cols="4" >IP</b-col>
                            <b-col cols="4" >設置場所</b-col>
                            <b-col cols="4" >
                                種別<SortColumnIcon v-model="testSortOrder.order"
                                                  @input="changeSort(testSortOrder)"
                                                  data-cy="テストソート"></SortColumnIcon>
                            </b-col>
                            </b-row>

                        </template>

                        <template #cell(DeviceList)="row">
                            <b-table
                                thead-class="d-none"
                                table-variant="light"
                                striped
                                :items="row.item.DeviceList"
                                :fields="DeviceListField"
                                responsive
                            ></b-table>
                        </template>
                    </b-table>
                </div>
                </div>
                <b-row>
                    <b-col offset="4" cols="4">
                    <pagination
                        :current-page="searchDeviceRequest.page"
                        :next-page="nextPage"
                        :per-page="searchDeviceRequest.perPage"
                        :previous-page="previousPage"
                        :total="total"
                        v-model="searchDeviceRequest.page"
                    ></pagination>
                    </b-col>
                    <b-row cols="2">
                    <PerPageInput v-model="searchDeviceRequest.perPage" :min-per-page="10"/>
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
import {EmptyWorkPlace, WorkPlace} from '@/types/device/WorkPlace';
import {EmptySlipType, SlipType} from '@/types/device/SlipType';
import {EmptyDeviceGetResponse, DeviceGetRequest, DeviceGetResponse} from '@/types/device/DeviceGet';
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
export default class DeviceMaintenance010 extends Vue {

  @Prop()
  facilityCodeList: WorkPlace | undefined;
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

  searchDeviceRequest: DeviceGetRequest = {
    workPlaceId: null,
    page: 1,
    perPage: 10,
    sorts: [this.testSortOrder],
  };
  searchDeviceList: DeviceGetResponse = {...EmptyDeviceGetResponse};

  hostDeviceFields = [
      {key: 'htHostName', label: '端末ホスト名'},
      {key: 'htHostIp', label: 'IPアドレス'},
      {key: 'deliveryWorkPlaceName', label: '工場(部門コード)'},
      {key: 'location', label: '設置場所'},
      {key: 'DeviceList', label: '端末'},
  ];

  DeviceListField = [
      {key: 'DeviceIp', label: '端末IPアドレス'},
      {key: 'location', label: '端末設置場所'},
  ];

  // computed

    // watch
    @Watch('searchDeviceRequest.page')
    onChangePage() {
        this.searchDevice();
    }
    // watch
    @Watch('searchDeviceRequest.perPage')
    onChangePerPage() {
        this.searchDeviceRequest.page = 1;
        this.searchDevice();
    }

  // method
  async initialize(): Promise<void> {
      await this.searchDevice();
  }

  async searchDevice(): Promise<void> {
        const getDeviceList = async (): Promise<void> => {
            await api.getDevice(this.searchDeviceRequest)
                .then((response: any) => {
                    this.searchDeviceList = response;
                    this.nextPage = this.searchDeviceList.page.nextPage;
                    this.previousPage = this.searchDeviceList.page.previousPage;
                    this.total = this.searchDeviceList.page.resultCount;
                });
        };
        await progress(getDeviceList);
    }

  toDetailPage(row: { htHostId: string; }) {
    const path = '/deviceMaintenance/details/' + row.htHostId;
    this.toPage(path);
  }
  toPage(page: string) {
      this.$router.push(page);
  }

  changeSort(request: SortOrderRequest): void {

      this.searchDeviceRequest.sorts
          .filter((sort) => sort.sort !== request.sort)
          .forEach((sort) => sort.order = SortOrder.NONE);
      if (request.order !== SortOrder.NONE) {
          this.searchDeviceRequest.sorts = [request];
      } else {
          this.searchDeviceRequest.sorts = [];
      }
      this.searchDevice();
  }

  // lifecycle hooks
  created(): void {
      this.searchDevice();
  }
}
</script>
