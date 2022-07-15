<template>
    <div>
    <b-navbar type="dark" variant="secondary">
        <b-navbar-brand href="#">ロゴ</b-navbar-brand>

        <b-navbar-nav class="ml-auto">
            <b-button class="my-2 my-sm-0" type="submit">ログアウト</b-button>
        </b-navbar-nav>
    </b-navbar>
</div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';

@Component({
  components: {
  },
})
export default class CommonHeader001 extends Vue {
  // data

  // computed


  // method
  async initialize(): Promise<void> {
    // await this.getSample();
  }

  // lifecycle hooks
  created(): void {
    this.initialize();
  }
}
</script>