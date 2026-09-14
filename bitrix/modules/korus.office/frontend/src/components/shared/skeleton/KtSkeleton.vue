<script lang="ts" setup>
import { h, useAttrs, defineSlots } from 'vue'
import { QSkeleton, QSkeletonProps, QSkeletonSlots } from 'quasar'

const props = defineProps<QSkeletonProps>()
const slots = defineSlots<QSkeletonSlots>()

function render () {
  const attrs = useAttrs()

  return h(QSkeleton, {
    ...props,
    ...attrs,
    class: 'kt-skeleton'
  }, {
    ...slots
  })
}
</script>

<template>
  <render />
</template>

<style lang="scss">
.kt-skeleton {
  &.q-skeleton--type-QBtn {
    height: var(--kt-ui-btn-size-sm);
    border-radius: var(--kt-ui-btn-radius);
  }
}
</style>
