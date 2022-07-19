<template>
  <div id="delivery-terminal-maintenance" data-cy="配送端末情報メンテナンス">
      <DeviceMaintenance010 :workplace-code-list="workplace">
          <slot></slot>
      </DeviceMaintenance010>

  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import DeviceMaintenance010 from '@/components/organisms/facility/device/DeviceMaintenance010.vue';
import api from '@/infrastructure/api/API';
import {EmptyWorkPlace, WorkPlace} from '@/types/device/WorkPlace';
import {progress} from '@/infrastructure/script/Progress';

@Component({
  components: {
      DeviceMaintenance010,
  },
})
export default class DeviceMaintenance extends Vue {
  // data
  workplace: WorkPlace = {...EmptyWorkPlace};


  // computed

  // method
  async initialize(): Promise<void> {
    await this.getBumonList();
  }

  async getBumonList(): Promise<void> {
        const getBumon = async (): Promise<void> => {
            await api.getWorkPlace()
                .then((response: any) => {
                    this.workplace = response;
                });
        };
        await progress(getBumon);
    }

  toPage(deviceRegister: string) {
      this.$router.push(deviceRegister);
  }
  // lifecycle hooks
  created(): void {
    this.initialize();
  }
}
</script>
