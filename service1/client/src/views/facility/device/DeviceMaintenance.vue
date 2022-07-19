<template>
  <div id="device-maintenance" data-cy="端末情報メンテナンス">
      <DeviceMaintenance010 :workplace-list="workplaceList">
          <slot></slot>
      </DeviceMaintenance010>

  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import DeviceMaintenance010 from '@/components/organisms/facility/device/DeviceMaintenance010.vue';
import api from '@/infrastructure/api/API';
import {EmptyWorkplace, Workplace} from '@/types/device/Workplace';
import {progress} from '@/infrastructure/script/Progress';

@Component({
  components: {
      DeviceMaintenance010,
  },
})
export default class DeviceMaintenance extends Vue {
  // data
  workplaceList: Array<Workplace> = [{...EmptyWorkplace}];


  // computed

  // method
  async initialize(): Promise<void> {
    await this.getBumonList();
  }

  async getBumonList(): Promise<void> {
        const getBumon = async (): Promise<void> => {
            await api.getWorkplace()
                .then((response: any) => {
                    this.workplaceList = response.workplaceList;
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
