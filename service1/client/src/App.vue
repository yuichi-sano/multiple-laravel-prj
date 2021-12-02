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
  created(): void {
    this.incompatibleRedirect();
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
      location.href = 'https://contents.jaai-e-learning.com/unsupported.html';
    }
  }
}
</script>

<style lang="scss">
  @import "@/assets/base";
</style>
