<template>
  <div>
    <b-form-group class="" :label-cols="labelCols" :label="labelName" :label-for="name">
      <b-form-datepicker :class="inputClassName" v-model="inputValue" class="mb-2" locale="ja"></b-form-datepicker>
    </b-form-group>
  </div>
</template>


<script lang="ts">
  import {Component, Prop, Vue} from 'vue-property-decorator';

  @Component({})
  export default class FormInputDatePicker extends Vue {
    @Prop({ type: String})
    name!: string;
    @Prop({ type: String})
    labelName!: string;
    @Prop({ type: String})
    labelCols!: string;
    @Prop({ type: String })
    value!: string;
    @Prop({ type: String})
    inputClassName!: string;
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
