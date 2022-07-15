<template>
  <div class="pagination-nav">
    <a v-if="hasPrevious"
       @click="previous()"
       data-cy="前へ">
      <b-icon icon="caret-left-fill"></b-icon>
    </a>
    <span>
      {{ fromNumber + '〜' + toNumber +'件表示/全'　+ toTotalNumber + '件' }}
    </span>
    <a v-if="hasNext"
       @click="next()"
       data-cy="次へ">
      <b-icon icon="caret-right-fill"></b-icon>
    </a>
  </div>
</template>

<script lang="ts">
import {Component, Emit, Prop, Vue} from 'vue-property-decorator';

@Component({
})
export default class Pagination extends Vue {
  @Prop()
  total!: number;
  @Prop()
  currentPage!: number;
  @Prop()
  perPage!: number;
  @Prop()
  nextPage!: number | null;
  @Prop()
  previousPage!: number | null;

  // computed
  get hasNext(): boolean {
    return !!this.nextPage;
  }

  get hasPrevious(): boolean {
    return !!this.previousPage;
  }

  get fromNumber(): string {
    if (!this.currentPage || !this.perPage) {
      return '';
    }
    return String(1 + ((this.currentPage - 1) * this.perPage));
  }

  get toNumber(): string {
    if (!this.currentPage || !this.perPage) {
      return '';
    }
    const toNumber = (this.currentPage) * this.perPage;
    if (this.total < toNumber) {
      return String(this.total);
    }
    return String(toNumber);
  }

  get toTotalNumber(): string {
    if (!this.total) {
      return '';
    }
    return String(((this.total)));
  }

  // method
  previous(): void {
    if (!this.previousPage) {
      return;
    }
    this.update(this.previousPage);
  }

  next(): void {
    if (!this.nextPage) {
      return;
    }
    this.update(this.nextPage);
  }

  @Emit('input')
  update(page: number): number {
    return page;
  }
}
</script>
