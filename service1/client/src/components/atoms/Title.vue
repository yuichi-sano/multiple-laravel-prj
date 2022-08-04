<template>
  <h2 class="mb-5">
    {{ title }}
  </h2>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from 'vue-property-decorator';

  @Component({})
  export default class Title extends Vue {
    @Prop({ type: String, required: true })
    title!: string;
  }
</script>