<script lang="ts" setup>
import { h, useAttrs, defineSlots } from 'vue'
import { QInnerLoading, QInnerLoadingProps, QInnerLoadingSlots } from 'quasar'

const props = defineProps<QInnerLoadingProps>()
const slots = defineSlots<QInnerLoadingSlots>()

function render () {
  const attrs = useAttrs()

  return h(QInnerLoading, {
    ...props,
    ...attrs,
    class: 'kt-inner-loading'
  })
}
</script>

<template>
  <render />
</template>

<style lang="scss">
@use 'vars';

/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-inner-loading {
    color: var(--_kt-loading-color);
    background-color: var(--_kt-loading-bg);
    z-index: 10;

    .q-spinner {
      color: var(--_kt-loading-spinner-color);
    }
  }
}
</style>
