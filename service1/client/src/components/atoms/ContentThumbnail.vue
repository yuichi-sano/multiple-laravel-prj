<template>
  <img :src="thumbnailUrl"/>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from 'vue-property-decorator';

  @Component({})
  export default class ContentThumbnail extends Vue {
    @Prop()
    id!: string;
    @Prop({default: ''})
    thumbnailUrl!: string;

    // method
    toContentDetail(): void {
      this.$router.push({path: `/content/${this.id}`}).catch(() => {
        // @ts-ignore block is empty.
      });
    }
  }
</script>
