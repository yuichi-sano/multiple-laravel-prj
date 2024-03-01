<template>
    <b-container id="masterMenu1-ouganism" class="mt-5" data-cy="">
        <p>マスター1メニュー</p>
    </b-container>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import Title from '@/components/atoms/Title.vue';
import Button from '@/components/atoms/Button.vue';
import api from '@/infrastructure/api/API';

@Component({
  components: {
    Title,
    Button,
  },
})
export default class TestMenu1 extends Vue {
  // data
  // computed

  // method
  async initialize(): Promise<void> {
  }
  toPage(menu: string) {
    this.$router.push(menu);
  }
  // lifecycle hooks
  created(): void {
    this.initialize();
  }
}
</script>