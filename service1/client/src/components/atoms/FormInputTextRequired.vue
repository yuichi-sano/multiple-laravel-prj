<template>
  <div class="row">
    <div class="col">
      <label :for="name" class="form-label">{{label}}</label>
    </div>
    <div class="col">
      <input type="text" class="form-control" :maxlength="maxLength" v-model="inputValue" :placeholder="placeholder" required>
    </div>
  </div>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from 'vue-property-decorator';

  @Component({})
  export default class FormInputText extends Vue {
    @Prop({ type: String})
    name!: string;
    @Prop({ type: String})
    label!: string;
    @Prop({ type: String })
    value!: string;
    @Prop({ type: Number })
    readonly maxLength!: number;
    @Prop({ type: Boolean, default: false })
    readonly hiraganaOnly!: boolean;
    @Prop({ type: String, default: '' })
    readonly placeholder!: string;

    get inputValue(): string {
        return this.value;
    }

    // セッター
    set inputValue(value: string) {
        this.$emit('input', value);
    }
}
</script>