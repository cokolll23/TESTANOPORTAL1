<script lang="ts" setup>
import { h, useAttrs, defineSlots } from 'vue'
import { QTabPanel, QTabPanelProps, QTabPanelSlots } from 'quasar'

const props = defineProps<QTabPanelProps>()
const slots = defineSlots<QTabPanelSlots>()

function render () {
  const attrs = useAttrs()

  return h(QTabPanel, {
    ...props,
    ...attrs,
    class: 'kt-tab-panel'
  }, {
    ...slots
  })
}
</script>

<template>
  <render />
</template>

<style lang="scss">
.kt-tab-panel {
}
</style>
