<template>
  <div id="zip-code-maintenance-menu" data-cy="メニュー">
      <ZipCodeMaintenanceMenu003>
          <slot></slot>
      </ZipCodeMaintenanceMenu003>

  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import ZipCodeMaintenanceMenu003 from '@/components/organisms/ZipCodeMaintenanceMenu.vue';
import api from '@/infrastructure/api/API';

@Component({
  components: {
      ZipCodeMaintenanceMenu003,
  },
})
export default class ZipCodeMaintenanceMenu extends Vue {
  // data
  sample = [];


  // computed

//   get isReadOnly(): boolean {
//     return loginModule.isReadOnly;
//   }

  // method
  async initialize(): Promise<void> {
    // await this.getSample();
  }

//   async getSample(): Promise<void> {
//       await api.getSample()
//         .then((response: any) => {
//           this.sample = response;
//         });
//   }

  // lifecycle hooks
  created(): void {
    this.initialize();
  }
}
</script>