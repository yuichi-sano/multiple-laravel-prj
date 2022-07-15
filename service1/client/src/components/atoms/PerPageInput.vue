<template>
    <input type="number" :min="minPerPage" :value="value" @change="update">
</template>
<script lang="ts">
  import {Component, Prop, Vue, Emit} from 'vue-property-decorator';

  @Component({})
  export default class PerPageInput extends Vue {
    // data
    @Prop({default: 10})
    minPerPage!: number | null;

    // prop
    @Prop({default: null})
    value!: number | null;

    @Emit('input')
    update(event: Event): string | null {
      const target = event.target as HTMLInputElement;
      if (target.value) {
        return target.value as string;
      }
      return null;
    }
  }
</script>
