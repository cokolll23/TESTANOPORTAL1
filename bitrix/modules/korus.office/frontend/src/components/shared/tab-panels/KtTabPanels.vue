<script lang="ts" setup>
import { ref, h, useAttrs, defineSlots } from 'vue'
import { QTabPanels, QTabPanelsProps, QTabPanelsSlots } from 'quasar'

const props = defineProps<QTabPanelsProps>()
const slots = defineSlots<QTabPanelsSlots>()

const qTabPanelsRef = ref<null | typeof QTabPanels>(null)

defineExpose({
  qTabPanelsRef
})

function render () {
  const attrs = useAttrs()

  return h(QTabPanels, {
    ...props,
    ...attrs,
    ref: qTabPanelsRef,
    class: 'kt-tab-panels'
  }, {
    ...slots
  })
}
</script>

<template>
  <render />
</template>

<style lang="scss">
.kt-tab-panels {
}
</style>
