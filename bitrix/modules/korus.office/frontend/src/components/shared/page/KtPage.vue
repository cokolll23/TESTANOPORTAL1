<script lang="ts" setup>
import { h, useAttrs, defineSlots } from 'vue'
import { QPage, QPageProps, QPageSlots } from 'quasar'

const props = defineProps<QPageProps>()
const slots = defineSlots<QPageSlots>()

function render () {
  const attrs = useAttrs()

  return h(QPage, {
    ...props,
    ...attrs,
    class: 'kt-page'
  }, {
    ...slots
  })
}
</script>

<template>
  <render />
</template>

<style lang="scss">
.kt-page {
}
</style>
