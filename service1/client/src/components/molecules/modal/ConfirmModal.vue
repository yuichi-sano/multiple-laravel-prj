<template>
  <modal :name="name"
         :draggable="true"
         :width="width"
         height="auto"
         :clickToClose="false">
    <p class="modal-card-title">{{title}}</p>
    <button class="delete"
            aria-label="close"
            @click="touch(false, $event)"
            @mousedown="touch(false, $event)"
            @touchstart="touch(false, $event)"></button>
    <div class="modal-text">
      <slot name="content"/>
    </div>
    <div class="modal-btn">
      <button @click="touch(true, $event)"
              @mousedown="touch(true, $event)"
              @touchstart="touch(true, $event)">はい</button>
      <button @click="touch(false, $event)"
              @mousedown="touch(false, $event)"
              @touchstart="touch(false, $event)">いいえ</button>
    </div>
  </modal>
</template>

<script lang="ts">
  import {Component, Emit, Prop, Vue} from 'vue-property-decorator';

  @Component({})
  export default class ConfirmModal extends Vue {
    @Prop({})
    name!: string;
    @Prop({})
    title!: string;
    @Prop({default: '340px'})
    width!: string;

    @Emit('confirm')
    confirm(result: boolean): boolean {
      return result;
    }

    // method
    touch(result: boolean, event: Event): void {
      this.confirm(result);
      event.preventDefault();
    }
  }
</script>
