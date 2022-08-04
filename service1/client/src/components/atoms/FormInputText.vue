<template>
  <div>
    <b-form-group class="" :label-cols="labelCols" :label="labelName" :label-for="name">
        <b-form-input id="input-text-default" :size="size" :class="inputClassName" v-model="inputValue" :maxlength="maxLength" :placeholder="placeholder"></b-form-input>
    </b-form-group>
  </div>
</template>


<script lang="ts">
  import {Component, Prop, Vue} from 'vue-property-decorator';

  @Component({})
  export default class FormInputText extends Vue {
    @Prop({ type: String})
    name!: string;
    @Prop({ type: String})
    labelName!: string;
    @Prop({ type: String})
    labelCols!: string;
    @Prop({ type:  [Number, String] })
    value!: string|number;
    @Prop({ type: String })
    readonly maxLength!: string;
    @Prop({ type: String})
    inputClassName!: string;
    @Prop({ type: String})
    size!: string;
    // @Prop({ type: Boolean, default: false })
    // readonly hiraganaOnly!: boolean;
    @Prop({ type: String, default: '' })
    readonly placeholder!: string;

    get inputValue(): string|number {
        return this.value;
    }

    // セッター
    set inputValue(value: string|number) {
        this.$emit('input', value);
    }
}
</script>
