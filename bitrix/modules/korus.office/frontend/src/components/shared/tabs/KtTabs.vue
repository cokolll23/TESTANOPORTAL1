<script lang="ts" setup>
import { h, useAttrs, defineSlots } from 'vue'
import { QTabs, QTabsProps, QTabsSlots } from 'quasar'

const props = defineProps<QTabsProps>()
const slots = defineSlots<QTabsSlots>()

function render () {
  const attrs = useAttrs()

  return h(QTabs, {
    ...props,
    ...attrs,
    class: 'kt-tabs'
  }, {
    ...slots
  })
}
</script>

<template>
  <render />
</template>

<style lang="scss">
.kt-tabs {
}
</style>
