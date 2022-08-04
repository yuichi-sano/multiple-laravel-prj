<template>
  <a v-if="isASC"
     @click="desc()">
      <b-icon icon="caret-up-fill"></b-icon>
  </a>
  <a v-else-if="isDESC"
     @click="none()">
      <b-icon icon="caret-down-fill"></b-icon>
  </a>
  <a v-else
     @click="asc()">
      <b-icon icon="chevron-expand"></b-icon>
  </a>
</template>

<script lang="ts">
  import {Component, Emit, Prop, Vue} from 'vue-property-decorator';
  import {SortOrder, SortOrderRequest} from '@/types/sort/SortOrder';

  @Component({})
  export default class SortColumnIcon extends Vue {
    @Prop()
    value!: SortOrder;

    // computed
    get isASC(): boolean {
      return this.value === SortOrder.ASC;
    }

    get isDESC(): boolean {
      return this.value === SortOrder.DESC;
    }

    // method
    asc(): void {
      this.update(SortOrder.ASC);
    }

    desc(): void {
      this.update(SortOrder.DESC);
    }

    none(): void {
      this.update(SortOrder.NONE);
    }

    @Emit('input')
    update(order: SortOrder): SortOrder | null {
      return order;
    }
  }
</script>
