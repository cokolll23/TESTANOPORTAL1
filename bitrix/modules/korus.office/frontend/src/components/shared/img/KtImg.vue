<script lang="ts" setup>
import { h, useAttrs, defineSlots } from 'vue'
import { QImg, QImgProps, QImgSlots } from 'quasar'

const props = defineProps<QImgProps>()
const slots = defineSlots<QImgSlots>()

function render () {
  const attrs = useAttrs()

  return h(QImg, {
    ...props,
    ...attrs,
    class: 'kt-img'
  }, {
    ...slots
  })
}
</script>

<template>
  <render />
</template>

<style lang="scss">
.kt-img {
}
</style>
