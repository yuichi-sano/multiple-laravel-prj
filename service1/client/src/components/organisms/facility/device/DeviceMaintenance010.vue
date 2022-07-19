<template>
  <b-container id="device-maintenance-organism" class="mt-5" data-cy="">

      <Title title="端末情報メンテナンス" class="text-center"></Title><br>

      <b-row>
        <b-col cols="12">

            <!-- 端末情報新規登録 -->
            <div class="div-section">
                <Button class="text-center" variantName="success" buttonText="端末情報新規登録" @click="toPage('/deviceMaintenance/register')"></Button>
            </div>

           <!-- 端末情報検索 -->
            <div class="div-section" id="workplace-search">
                <TitleSmall titleSmall="端末情報検索" class="text-center"></TitleSmall>
                <b-row>
                    <b-col cols="9">

                         <FormSelect class="pl-4" name="workplace-select"
                                labelCols="4" labelName="拠点" inputClassName=""
                                v-model="searchDeviceRequest.workplaceId"
                                :options="workplaceList"
                                text-field='workplaceName'
                                value-field='workplaceId'></FormSelect>
                    </b-col>
                    <b-col cols="3">
                        <Button class="" variantName="info" buttonText="検索" @click="searchDevice()"></Button><br>
                    </b-col>

                </b-row>
                <div id="table-container" class="mt-3">
                    <div  class="mt-3" v-if="searchDeviceList.deviceList.length > 0">
                    <b-table hover
                             table-variant="info"
                             :bordered="true"
                             responsive
                             :items="searchDeviceList.deviceList"
                             :fields="deviceFields"
                             selectable
                             @row-clicked="toDetailPage"
                             >
                        <template #cell(name)="row">
                            <p>{{row.item.name}}</p>
                        </template>
                        <template #cell(ip)="row">
                            <p>{{row.item.ip}}</p>
                        </template>
                        <template #cell(workplaceId)="row">
                            <p>{{row.item.workplaceId}}</p>
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
import {EmptyWorkplace, Workplace} from '@/types/device/Workplace';
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
  workplaceList: Array<Workplace> | undefined;
    testSortOrder: SortOrderRequest = {
        sort: 'test',
        order: SortOrder.NONE,
    };
  // data
  sample = [];
  total: number = 0;
  nextPage: number | null = null;
  previousPage: number | null = null;

  searchDeviceRequest: DeviceGetRequest = {
    workplaceId: null,
    page: 1,
    perPage: 10,
    sorts: [this.testSortOrder],
  };
  searchDeviceList: DeviceGetResponse = {...EmptyDeviceGetResponse};

  deviceFields = [
      {key: 'name', label: '端末名'},
      {key: 'ip', label: 'IPアドレス'},
      {key: 'workplaceId', label: '拠点'},
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

  toDetailPage(row: { id: number; }) {
    const path = '/deviceMaintenance/details/' + row.id;
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
