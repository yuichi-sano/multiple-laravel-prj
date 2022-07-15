<template>
  <div id="button">
    <b-button  :class="className" :size="size" :variant="variantName" @click="handleClick">{{ buttonText }}</b-button>
  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue } from 'vue-property-decorator';

@Component({})
export default class Button extends Vue {
  @Prop({ type: String, required: true })
  buttonText!: string;
  @Prop({ type: String, default: '' })
  type!: string;
  @Prop({ type: String, default: '' })
  className!: string;
  @Prop({ type: String, default: '' })
  variantName!: string;
  @Prop({ type: String, default: '' })
  size!: string;

  private handleClick() {
      this.$emit('click');
  }
}
</script>
