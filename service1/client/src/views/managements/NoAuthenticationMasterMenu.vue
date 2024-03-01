<template>
    <b-container id="NoAuthenticationMasterMenu" data-cy="マスターメニュー">
        <MasterMenu2></MasterMenu2>
    </b-container>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import MasterMenu2 from '@/components/organisms/MasterMenu2.vue';
import api from '@/infrastructure/api/API';

@Component({
  components: {
    MasterMenu2,
  },
})
export default class TopMenu extends Vue {
  // data
  // computed
  // method
  async initialize(): Promise<void> {
  }
  // lifecycle hooks
  created(): void {
    this.initialize();
  }
}
</script>