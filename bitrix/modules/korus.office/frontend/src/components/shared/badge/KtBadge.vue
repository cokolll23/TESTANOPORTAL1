<script lang="ts" setup>
import { h, useAttrs, defineSlots } from 'vue'
import { QBadge, QBadgeProps, QBadgeSlots } from 'quasar'

const props = defineProps<QBadgeProps>()
const slots = defineSlots<QBadgeSlots>()

function render () {
  const attrs = useAttrs()

  return h(QBadge, {
    ...props,
    ...attrs,
    class: 'kt-badge'
  }, {
    ...slots
  })
}
</script>

<template>
  <render />
</template>
