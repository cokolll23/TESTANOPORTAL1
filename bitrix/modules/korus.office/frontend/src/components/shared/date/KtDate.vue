<script lang="ts" setup>
import { ref, h, useAttrs, defineSlots } from 'vue'
import { QDate, QDateProps, QDateSlots } from 'quasar'

const props = withDefaults(defineProps<QDateProps>(), {
  mask: 'YYYY/MM/DD',
  calendar: 'gregorian',
  defaultView: 'Calendar',
  dark: null,
  color: 'primary'
})
const slots = defineSlots<QDateSlots>()

const qDateRef = ref<QDate>()

defineExpose({
  qDateRef
})

function render () {
  const attrs = useAttrs()

  return h(QDate, {
    ...props,
    ...attrs,
    ref: qDateRef,
    class: 'kt-date'
  }, {
    ...slots
  })
}
</script>

<template>
  <render />
</template>

<style lang="scss">
.kt-date {
}
</style>
