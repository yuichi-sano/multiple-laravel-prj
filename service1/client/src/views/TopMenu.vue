<template>
  <b-container id="topMenu" data-cy="メニュー">
    <Menu002>
    </Menu002>

  </b-container>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import Menu002 from '@/components/organisms/Menu002.vue';
import api from '@/infrastructure/api/API';

@Component({
  components: {
    Menu002,
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
