<template>
  <h5 class="mb-3 font-weight-bold">
    {{ titleSmall }}
  </h5>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from 'vue-property-decorator';

  @Component({})
  export default class TitleSmall extends Vue {
    @Prop({ type: String, required: true })
    titleSmall!: string;
  }
</script>