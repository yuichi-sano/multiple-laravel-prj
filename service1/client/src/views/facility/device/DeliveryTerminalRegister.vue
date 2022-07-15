<template>
  <div id="delivery-terminal-register" data-cy="配送端末情報新規登録">
      <DeliveryTerminalRegister012 :bumon="bumon" :slipType="slipType">
      </DeliveryTerminalRegister012>

  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import DeliveryTerminalRegister012 from '@/components/organisms/facility/device/DeliveryTerminalRegister012.vue';
import {EmptyBumonCode, BumonCode} from '@/types/htDevice/BumonCode';
import {EmptySlipType, SlipType} from '@/types/htDevice/SlipType';
import api from '@/infrastructure/api/API';
import {progress} from '@/infrastructure/script/Progress';

@Component({
  components: {
      DeliveryTerminalRegister012,
  },
})
export default class DeliveryTerminalRegister extends Vue {
  // data

  bumon: BumonCode = {...EmptyBumonCode};
  slipType: SlipType = {...EmptySlipType};


  // computed
  // method
  async initialize(): Promise<void> {
    await this.getBumonCodeList();
    await this.getSlipTypeList();
  }

  async getBumonCodeList(): Promise<void> {
    const getBumon = async (): Promise<void> => {
        await api.getBumonCode()
            .then((response: any) => {
                this.bumon = response;
            });
    };
    await progress(getBumon);
  }

  async getSlipTypeList(): Promise<void> {
    const getSlip = async (): Promise<void> => {
        await api.getSlipType()
            .then((response: any) => {
                this.slipType = response;
            });
    };
    await progress(getSlip);
  }

  // lifecycle hooks
  created(): void {
    this.initialize();
  }
}
</script>
