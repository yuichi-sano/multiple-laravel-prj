<template>
  <div id="device-register" data-cy="端末情報新規登録">
      <DeviceRegister012 :workplace="workplace" >
      </DeviceRegister012>

  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import DeviceRegister012 from '@/components/organisms/facility/device/DeviceRegister012.vue';
import {EmptyWorkplace, Workplace} from '@/types/device/Workplace';
import api from '@/infrastructure/api/API';
import {progress} from '@/infrastructure/script/Progress';

@Component({
  components: {
      DeviceRegister012,
  },
})
export default class DeviceRegister extends Vue {
  // data

  workplace: Array<Workplace> = [{...EmptyWorkplace}];


  // computed
  // method
  async initialize(): Promise<void> {
    await this.getWorkplaceList();
  }

  async getWorkplaceList(): Promise<void> {
    const getWorkplaceList = async (): Promise<void> => {
        await api.getWorkplace()
            .then((response: any) => {
                this.workplace = response;
            });
    };
    await progress(getWorkplaceList);
  }

  // lifecycle hooks
  created(): void {
    this.initialize();
  }
}
</script>
