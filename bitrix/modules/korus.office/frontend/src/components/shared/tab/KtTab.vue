<script lang="ts" setup>
import { h, useAttrs, defineSlots } from 'vue'
import { QTab, QTabProps, QTabSlots } from 'quasar'

const props = defineProps<QTabProps>()
const slots = defineSlots<QTabSlots>()

function render () {
  const attrs = useAttrs()

  return h(QTab, {
    ...props,
    ...attrs,
    class: 'kt-tab'
  }, {
    ...slots
  })
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
  .kt-tab {
    min-height: initial;
    padding: 0;

    &.q-tab--inactive {
      opacity: 1;
    }

    &:not(:last-child)::after {
      content: '';
      width: 1px;
      height: 16px;
      margin: 0 var(--kt-ui-offset-md);
      background-color: var(--kt-ui-border-subtle-00);
    }

    .q-focus-helper {
      display: none;
    }

    .q-tab__content {
      padding: 0;
    }

    .q-tab__label {
      font-weight: 400;
    }

    &.q-tab--inactive .q-tab__label {
      color: var(--kt-ui-gray-70);
    }

    .q-tab__indicator {
      display: none;
    }
  }
}
</style>
