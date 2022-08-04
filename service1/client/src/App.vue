<template>
  <div id="app">
    <Header/>
    <div class="container">
      <router-view/>
    </div>
    <ScreenGuard v-if="isProcessing"></ScreenGuard>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import api from '@/infrastructure/api/API';
import Header from '@/components/organisms/Header.vue';
import ScreenGuard from '@/components/atoms/ScreenGuard.vue';
import {processModule} from '@/stores/process/Process';

@Component({
  components: {
    Header,
    ScreenGuard,
  },
})
export default class App extends Vue {
  healthChecker: number | null | undefined;
  created(): void {
    this.incompatibleRedirect();
    this.startHealthCheck();
  }
  // computed
  get isProcessing(): boolean {
      return processModule.isProcessing;
  }
  isIE(): boolean {
    const userAgent = window.navigator.userAgent.toUpperCase();
    return (userAgent.indexOf('MSIE') !== -1  || userAgent.indexOf('TRIDENT') !== -1);
  }
  incompatibleRedirect(): void {
    if (this.isIE()) {
      location.href = 'https://google.com';
    }
  }
  startHealthCheck() {
    // @ts-ignore @FIXME
      this.healthChecker = this.$setInterval(() => {
          this.healthCheck();
      }, 5000);
  }

  async healthCheck(): Promise<void> {
    const id = this.healthChecker;
    await api.healthCheck()
    .then((response: any) => {
    }).catch((reason) => {
        // @ts-ignore @FIXME
      this.$clearInterval(id);
    });
  }

}
</script>

<style lang="scss">
  @import "@/assets/base";
</style>
