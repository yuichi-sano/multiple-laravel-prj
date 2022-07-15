<template>
  <select :value="value"
          @change="update"
          data-cy="表示件数">
    <option v-for="perPage in this.perPages"
            :key="perPage"
            :value="perPage"
            :selected="value === perPage">
      {{ perPage }}
    </option>
  </select>
</template>
<script lang="ts">
  import {Component, Prop, Vue, Emit} from 'vue-property-decorator';
  import {PerPages} from '@/types/page/Page';

  @Component({})
  export default class PerPagesSelector extends Vue {
    // data
    perPages: number[] = PerPages;

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
