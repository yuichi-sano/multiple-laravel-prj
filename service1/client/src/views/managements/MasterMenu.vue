<template>
    <b-container id="MasterMenu" data-cy="マスターメニュー">
        <MasterMenu1></MasterMenu1>
    </b-container>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import MasterMenu1 from '@/components/organisms/MasterMenu1.vue';
import api from '@/infrastructure/api/API';

@Component({
  components: {
    MasterMenu1,
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