<template>
  <div id="delivery-terminal-details" data-cy="配送端末情報詳細">
    <DeviceDetails011 v-if="initalized" :workplace="workplace" :detail-id="detailId">
      </DeviceDetails011>
  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import DeviceDetails011 from '@/components/organisms/facility/device/DeviceDetails011.vue';
import api from '@/infrastructure/api/API';
import {EmptyWorkPlace, WorkPlace} from '@/types/device/WorkPlace';
import {EmptySlipType, SlipType} from '@/types/device/SlipType';
import {progress} from '@/infrastructure/script/Progress';

@Component({
  components: {
      DeviceDetails011,
  },
})
export default class DeviceDetails extends Vue {
  // data
  initalized = false;
  workplace: WorkPlace[] = [EmptyWorkPlace];
  detailId: string = '';
  // computed

  // method
  async initialize(): Promise<void> {
    this.detailId = this.$route.params.id;
    await this.getWorkPlaceList();
    this.initalized = true;
  }

  async getWorkPlaceList(): Promise<void> {
    const getBumon = async (): Promise<void> => {
        await api.getWorkPlace()
            .then((response: any) => {
                this.workplace = response.facilityCodeList;
            });
    };
    await progress(getBumon);
  }


  // lifecycle hooks
  async created(): Promise<void> {
    await this.initialize();
  }
}
</script>