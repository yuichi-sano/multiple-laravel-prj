<template>
  <div id="delivery-terminal-maintenance" data-cy="配送端末情報メンテナンス">
      <DeliveryTerminalMaintenance010 :bumon-code-list="bumon">
          <slot></slot>
      </DeliveryTerminalMaintenance010>

  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import DeliveryTerminalMaintenance010 from '@/components/organisms/facility/device/DeliveryTerminalMaintenance010.vue';
import api from '@/infrastructure/api/API';
import {EmptyBumonCode, BumonCode} from '@/types/htDevice/BumonCode';
import {progress} from '@/infrastructure/script/Progress';

@Component({
  components: {
      DeliveryTerminalMaintenance010,
  },
})
export default class DeliveryTerminalMaintenance extends Vue {
  // data
  bumon: BumonCode = {...EmptyBumonCode};


  // computed

  // method
  async initialize(): Promise<void> {
    await this.getBumonList();
  }

  async getBumonList(): Promise<void> {
        const getBumon = async (): Promise<void> => {
            await api.getBumonCode()
                .then((response: any) => {
                    this.bumon = response;
                });
        };
        await progress(getBumon);
    }

  toPage(deliveryTerminalRegister: string) {
      this.$router.push(deliveryTerminalRegister);
  }
  // lifecycle hooks
  created(): void {
    this.initialize();
  }
}
</script>
