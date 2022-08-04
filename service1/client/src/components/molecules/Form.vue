<template>
  <div id='form-section'>
    <form :model="formData">
      <slot></slot>
    </form>
  </div>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from 'vue-property-decorator';

  @Component({})
  export default class Form extends Vue {
    @Prop({required: true})
    formData!: object;
  }
</script>
