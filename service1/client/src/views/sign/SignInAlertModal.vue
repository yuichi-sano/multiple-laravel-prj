<template>
  <modal :name="name"
         :width="width"
         height="auto"
         :clickToClose="false"
         :draggable="true">
    <p class="modal-card-title">{{title}}</p>
    <div class="modal-text information-content">
      <slot name="content"/>
    </div>
    <div class="modal-btn">
      <button @click="touch($event)"
              @mousedown="touch($event)"
              @touchstart="touch($event)">ページ遷移</button>
    </div>
  </modal>
</template>

<script lang="ts">
  import {Component, Emit, Prop, Vue} from 'vue-property-decorator';

  @Component({})
  export default class SignInAlertModal extends Vue {
    @Prop({})
    name!: string;
    @Prop({})
    title!: string;
    @Prop({default: '340px'})
    width!: string;

    @Emit('done')
    done(): void {
      // emit
    }

    // method
    touch(event: Event): void {
      this.done();
      event.preventDefault();
    }
  }
</script>

<style lang="scss" scoped>
  .information-content {
    color: #e20000;
    white-space:pre-wrap;
    word-wrap:break-word;
  }
  .modal-btn {
    width: inherit;
    padding: 0 90px 20px !important;
  }
</style>
