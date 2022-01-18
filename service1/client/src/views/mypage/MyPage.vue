<template>
  <div id="myPage"
       data-cy="マイページ画面">
    <h1>マイページ</h1>
    <p class="mypageCaption">MyPage</p>
  </div>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator';
import {loginModule} from '@/stores/authentication/Account';
import api from '@/infrastructure/api/API';

@Component({
  components: {},
})
export default class Home extends Vue {
  // data
  sample =[];

  // computed

  get isReadOnly(): boolean {
    return loginModule.isReadOnly;
  }

  // method
  async initialize(): Promise<void> {
    await this.getSample();
  }

  async getSample(): Promise<void> {
      await api.getSample()
        .then((response: any) => {
          this.sample = response;
        });
  }

  // lifecycle hooks
  created(): void {
    this.initialize();
  }
}
</script>
