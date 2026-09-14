<script lang="ts" setup>
import { ref, h, useAttrs, defineSlots } from 'vue'
import { QForm, QFormProps, QFormSlots } from 'quasar'

const props = defineProps<QFormProps>()
const slots = defineSlots<QFormSlots>()
const qFormRef = ref<null | typeof QForm>(null)

defineExpose({
  qFormRef
})

function render () {
  const attrs = useAttrs()

  return h(QForm, {
    ...props,
    ...attrs,
    ref: qFormRef,
    class: 'kt-form'
  }, {
    ...slots
  })
}
</script>

<template>
  <render />
</template>

<style lang="scss">
.kt-form {
}
</style>
