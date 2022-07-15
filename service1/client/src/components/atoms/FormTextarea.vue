<template>
  <div>
    <b-form-group class="" :label-cols="labelCols" :label="labelName" :label-for="name">
        <b-form-textarea id="input-text-default" :rows="rowNumber" :class="inputClassName" v-model="inputValue" :placeholder="placeholder"></b-form-textarea>
    </b-form-group>
  </div>
</template>


<script lang="ts">
  import {Component, Prop, Vue} from 'vue-property-decorator';

  @Component({})
  export default class FormTextarea extends Vue {
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
    @Prop({ type: String})
    rowNumber!: string;
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