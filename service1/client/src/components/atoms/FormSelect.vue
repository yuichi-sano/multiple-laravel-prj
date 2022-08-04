<template>
  <div>
    <b-form-group class="" :label-cols="labelCols" :label="labelName" :label-for="name">
        <b-form-select
            :class="inputClassName"
            v-model="selected"
            :options="options"
            :text-field="textField"
            :value-field="valueField"
        >
        </b-form-select>
    </b-form-group>
  </div>
</template>


<script lang="ts">
  import {Component, Prop, Vue} from 'vue-property-decorator';

  @Component({})
  export default class FormSelect extends Vue {
    @Prop({ type: String})
    name!: string;
    @Prop({ type: String})
    labelName!: string;
    @Prop({ type: String})
    labelCols!: string;
    @Prop({ type: [Number, String]  })
    value!: string|number;
    @Prop({ type: String})
    inputClassName!: string;
    @Prop({ type: String, default: '' })
    readonly placeholder!: string;

    @Prop({ type: String, default: 'text' })
    readonly textField!: string;
    @Prop({ type: String, default: 'value' })
    readonly valueField!: string;

    @Prop({ type: Array})
    options: [] | undefined;

    get selected(): string|number {
        return this.value;
    }

    // セッター
    set selected(value: string|number) {
        this.$emit('input', value);
    }
}
</script>
