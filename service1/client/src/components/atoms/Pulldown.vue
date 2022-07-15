<template>
  <div class="row">
    <!-- <b-row class="my-1"> -->
      <b-col sm="2">
        <label :for="name">{{label}}</label>
      </b-col>
      <b-col sm="4">
        <b-form-select id="form-input-select" v-model="selectedValue" :options="options">
            <b-form-select-option :value="null">選択してください</b-form-select-option>
        </b-form-select>
      </b-col>
    <!-- </b-row> -->
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
// import { MSG_COMMON_INPUT_001 } from '@/const/error/common/ErrorConst';
// import { Rules } from '@/types/form/Rules';
// import { PulldownOptions } from '@/types/form/PulldownOptions';

@Component
export default class Pulldown extends Vue {
    @Prop({ type: String})
    name!: string;
    @Prop({ type: String})
    label!: string;
    @Prop({ type: [ String, Number ]})
    value!: string | number;
    // @Prop({ type: Array })
    // options!: [];
    @Prop({ type: String })
    placeholder!: string;

    get inputValue(): string | number {
        return this.value;
    }

    // セッター
    set inputValue(value: string | number) {
        this.$emit('input', value);
    }
    // @ts-ignore @TODO
    private rules: Rules = [
        // { required: true, message: MSG_COMMON_INPUT_001 },
    ];
}
</script>