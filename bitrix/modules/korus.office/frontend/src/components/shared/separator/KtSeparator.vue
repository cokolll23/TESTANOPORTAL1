<script lang="ts" setup>
import { h, useAttrs } from 'vue'
import { QSeparator, QSeparatorProps } from 'quasar'

const props = defineProps<QSeparatorProps>()

function render () {
  const attrs = useAttrs()

  return h(QSeparator, {
    ...props,
    ...attrs,
    class: 'kt-separator'
  })
}
</script>

<template>
  <render />
</template>

<style lang="scss">
.kt-separator {
}
</style>
