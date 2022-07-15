<template>
  <a v-if="isOpen"
     class="open"
     @click="close()">
    <b-icon id="chevron-down"/>
  </a>
  <a v-else
     @click="open()">
    <b-icon id="chevron-left"/>
  </a>
</template>

<script lang="ts">
  import {Component, Emit, Prop, Vue} from 'vue-property-decorator';

  @Component({})
  export default class ToggleIcon extends Vue {
    @Prop()
    value!: boolean;

    // computed
    get isOpen(): boolean {
      return this.value;
    }

    get isClose(): boolean {
      return !this.value;
    }

    // method
    open(): void {
      this.update(true);
    }

    close(): void {
      this.update(false);
    }

    @Emit('input')
    update(toggle: boolean): boolean {
      return toggle;
    }
  }
</script>
