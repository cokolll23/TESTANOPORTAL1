<script lang="ts" setup>
import { ref, h, useAttrs, defineSlots } from 'vue'
import { QExpansionItem, QExpansionItemProps, QExpansionItemSlots } from 'quasar'

const props = defineProps<QExpansionItemProps>()
const slots = defineSlots<QExpansionItemSlots>()

const qExpansionItem = ref<null | QExpansionItem>(null)

defineExpose({
  qExpansionItem
})

function render () {
  const attrs = useAttrs()

  return h(QExpansionItem, {
    ...props,
    ...attrs,
    ref: qExpansionItem,
    class: 'kt-expansion-item'
  }, {
    ...slots
  })
}
</script>

<template>
  <render />
</template>

<style lang="scss">
.kt-expansion-item {
}
</style>
