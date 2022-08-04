<template>
  <b-container id="zip-code-maintenance-menu-organism" class="mt-5" data-cy="">
    
      <Title title="郵便番号メンテナンスメニュー一覧" class="text-center"></Title><br>

      <b-container id="zip-code-maintenance-menu" class="text-center">
            <Button className="zip-code-menu-button m-1" size="lg" variantName="outline-info" buttonText="VIEW-004 郵政郵便番号マスタメンテナンス" @click="toPage('/zipCodeMaintenance/yuusei')"></Button>
      </b-container>

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
export default class ZipCodeMaintenanceMenu003 extends Vue {
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

  toPage(menu: string) {
      this.$router.push(menu);
  }

  // lifecycle hooks
  created(): void {
    this.initialize();
  }
}
</script>