<template>
    <b-container id="testMenu" data-cy="テストメニュー">
        <TestMenu1></TestMenu1>
    </b-container>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import TestMenu1 from '@/components/organisms/TestMenu1.vue';
import api from '@/infrastructure/api/API';

@Component({
  components: {
    TestMenu1,
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