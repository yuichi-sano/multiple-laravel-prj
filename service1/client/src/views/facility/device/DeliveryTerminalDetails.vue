<template>
  <div id="delivery-terminal-details" data-cy="配送端末情報詳細">
    <DeliveryTerminalDetails011 v-if="initalized" :bumon="bumon" :slipType="slipType" :detail-id="detailId">
      </DeliveryTerminalDetails011>
  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import DeliveryTerminalDetails011 from '@/components/organisms/facility/device/DeliveryTerminalDetails011.vue';
import api from '@/infrastructure/api/API';
import {EmptyBumonCode, BumonCode} from '@/types/htDevice/BumonCode';
import {EmptySlipType, SlipType} from '@/types/htDevice/SlipType';
import {progress} from '@/infrastructure/script/Progress';

@Component({
  components: {
      DeliveryTerminalDetails011,
  },
})
export default class DeliveryTerminalDetails extends Vue {
  // data
  initalized = false;
  bumon: BumonCode[] = [EmptyBumonCode];
  slipType: SlipType[] = [EmptySlipType];
  detailId: string = '';
  // computed

  // method
  async initialize(): Promise<void> {
    this.detailId = this.$route.params.id;
    await this.getBumonCodeList();
    await this.getSlipTypeList();
    this.initalized = true;
  }

  async getBumonCodeList(): Promise<void> {
    const getBumon = async (): Promise<void> => {
        await api.getBumonCode()
            .then((response: any) => {
                this.bumon = response.facilityCodeList;
            });
    };
    await progress(getBumon);
  }

  async getSlipTypeList(): Promise<void> {
    const getSlip = async (): Promise<void> => {
      await api.getSlipType()
        .then((response: any) => {
          this.slipType = response.slipTypeList;
        });
    };
    await progress(getSlip);
  }

  // lifecycle hooks
  async created(): Promise<void> {
    await this.initialize();
  }
}
</script>