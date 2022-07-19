<template>
  <b-container id="menu-organism" class="mt-5" data-cy="">

    <Title title="マスタメンテナンス業務システム" class="text-center"></Title><br>

    <b-row>
      <b-col cols="3">
        <b-dropdown split split-href="#" @click="toPage('/zipCodeMaintenance/menu')" text="VIEW-003 郵便番号関連情報マスタメンテナンス" variant="outline-info" class="top-menu-button m-2" size="lg">
          <b-dropdown-item @click="toPage('/zipCodeMaintenance/yuusei')">VIEW-004 郵政郵便番号マスタメンテナンス</b-dropdown-item>
        </b-dropdown>
      </b-col>
      <b-col cols="3">
        <Button className="top-menu-button m-2" size="lg" variantName="outline-info" buttonText="VIEW-010 端末情報マスタメンテナンス" @click="toPage('/deviceMaintenance')"></Button><br>
      </b-col>
    </b-row>

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
export default class Menu002 extends Vue {
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
