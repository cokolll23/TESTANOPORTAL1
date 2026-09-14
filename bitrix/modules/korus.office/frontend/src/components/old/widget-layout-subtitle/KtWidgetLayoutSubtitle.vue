<template>
  <h3 class="kt-widget-layout-subtitle">
    <slot>{{ props.text }}</slot>
  </h3>
</template>

<script lang="ts" setup>
interface IProps {
  text?: string;
}

const props = defineProps<IProps>()
</script>

<style lang="scss">
.pgk {
  .kt-widget-layout-subtitle {
    $font-weight: 600;
    $font-size: 13px;
    $line-height: 20px;

    font-weight: $font-weight;
    font-size: $font-size;
    line-height: $line-height;

    @media screen and (min-width: $breakpoint-lg) {
      $font-size: 16px;
      $line-height: 22px;

      font-size: $font-size;
      line-height: $line-height;
    }
  }
}
</style>
