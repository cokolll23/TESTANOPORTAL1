<script lang="ts" setup>
import { h, useAttrs, defineSlots, type VNode } from 'vue'

interface IProps {
  level: number;
  text?: string;
}

interface ISlots {
  default?: () => VNode | VNode[]
}

const props = defineProps<IProps>()
const slots = defineSlots<ISlots>()

function render () {
  const attrs = useAttrs()

  return h('h' + props.level, {
    ...attrs,
    class: [
      `kt-title kt-text-h${props.level}`
    ]
  }, typeof slots.default === 'function' ? slots.default() : props.text)
}
</script>

<template>
  <render />
</template>

<style lang="scss">
/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-text {
    &-h1,
    &-h2,
    &-h3 {
      margin: 0;
      font-family: 'Montserrat', sans-serif;
      font-size: var(--kt-title-font-size);
      line-height: var(--kt-title-line-height);
      letter-spacing: initial;
      color: var(--kt-ui-text-primary);
    }

    &-h1,
    &-h2 {
      font-weight: 700;
    }

    &-h1 {
      --kt-title-font-size: 28px;
      --kt-title-line-height: 1.35;
    }

    &-h2 {
      --kt-title-font-size: 20px;
      --kt-title-line-height: 1.35;
    }

    &-h3 {
      --kt-title-font-size: 14px;
      --kt-title-line-height: 1.2;
    }
  }
}
</style>
