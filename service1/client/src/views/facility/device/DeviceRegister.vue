<template>
  <div id="delivery-terminal-register" data-cy="配送端末情報新規登録">
      <DeviceRegister012 :workplace="workplace" >
      </DeviceRegister012>

  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import DeviceRegister012 from '@/components/organisms/facility/device/DeviceRegister012.vue';
import {EmptyWorkPlace, WorkPlace} from '@/types/device/WorkPlace';
import {EmptySlipType, SlipType} from '@/types/device/SlipType';
import api from '@/infrastructure/api/API';
import {progress} from '@/infrastructure/script/Progress';

@Component({
  components: {
      DeviceRegister012,
  },
})
export default class DeviceRegister extends Vue {
  // data

  workplace: WorkPlace = {...EmptyWorkPlace};


  // computed
  // method
  async initialize(): Promise<void> {
    await this.getWorkPlaceList();
  }

  async getWorkPlaceList(): Promise<void> {
    const getBumon = async (): Promise<void> => {
        await api.getWorkPlace()
            .then((response: any) => {
                this.workplace = response;
            });
    };
    await progress(getBumon);
  }

  // lifecycle hooks
  created(): void {
    this.initialize();
  }
}
</script>
