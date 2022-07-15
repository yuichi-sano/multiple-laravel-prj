<template>
  <div>
    <b-form-group class="" :label-cols="labelCols" :label="labelName" :label-for="name">
        <b-form-input 
          id="password-input-default" 
          type="password" 
          :class="inputClassName" 
          v-model="inputValue" 
          :maxlength="maxLength" 
          :placeholder="placeholder">
        </b-form-input>
    </b-form-group>
  </div>
</template>


<script lang="ts">
  import {Component, Prop, Vue} from 'vue-property-decorator';

  @Component({})
  export default class FormInputPassword extends Vue {
    @Prop({ type: String})
    name!: string;
    @Prop({ type: String})
    labelName!: string;
    @Prop({ type: Number})
    labelCols!: number;
    @Prop({ type: String })
    value!: string;
    @Prop({ type: Number })
    readonly maxLength!: number;
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