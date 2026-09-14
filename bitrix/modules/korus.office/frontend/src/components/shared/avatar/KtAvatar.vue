<script lang="ts" setup>
import { h, useAttrs, defineSlots } from 'vue'
import { QAvatar, QAvatarProps, QAvatarSlots } from 'quasar'

const props = defineProps<QAvatarProps>()
const slots = defineSlots<QAvatarSlots>()

function render () {
  const attrs = useAttrs()

  return h(QAvatar, {
    ...props,
    ...attrs,
    class: 'kt-avatar'
  }, {
    ...slots
  })
}
</script>

<template>
  <render />
</template>

<style lang="scss">
.kt-avatar {
}
</style>
