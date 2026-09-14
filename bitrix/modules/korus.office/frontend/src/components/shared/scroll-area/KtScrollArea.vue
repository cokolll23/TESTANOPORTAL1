<script lang="ts" setup>
import { h, ref, useAttrs, defineSlots } from 'vue'
import { QScrollArea, QScrollAreaProps, QScrollAreaSlots } from 'quasar'

const props = defineProps<QScrollAreaProps>()
const slots = defineSlots<QScrollAreaSlots>()

const qScrollArea = ref<null | typeof QScrollArea>(null)

defineExpose({
  qScrollArea
})

function render () {
  const attrs = useAttrs()

  return h(QScrollArea, {
    ...props,
    ...attrs,
    ref: qScrollArea,
    class: 'kt-scrollarea'
  }, {
    ...slots
  })
}
</script>

<template>
  <render />
</template>

<style lang="scss">
.kt-scrollarea {
  padding-right: var(--kt-ui-offset-lg);

  .q-scrollarea__bar,
  .q-scrollarea__thumb {
    opacity: 1;
    cursor: default;
  }

  .q-scrollarea__bar {
    background-color: transparent;
    transition: background-color 160ms ease-in-out;
  }

  .q-scrollarea__bar:hover {
    background-color: var(--kt-ui-scrollbar-rail-color);
  }

  .q-scrollarea__bar--v {
    width: var(--kt-ui-scrollbar-thumb-size);
  }

  .q-scrollarea__bar--h {
    height: var(--kt-ui-scrollbar-thumb-size);
  }

  .q-scrollarea__thumb {
    border-radius: calc(var(--kt-ui-scrollbar-thumb-size-active) / 2);
    background-color: var(--kt-ui-scrollbar-thumb-color);
  }

  .q-scrollarea__thumb:active {
    background-color: var(--kt-ui-scrollbar-thumb-color-active);
  }

  .q-scrollarea__thumb::after {
    content: '';
    width: 6px;
    height: 7px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    background: var(--kt-ui-icon-scrollbar-handler-y) center no-repeat;
    transition: opacity 200ms ease-in-out;
  }

  .q-scrollarea__thumb--v {
    width: var(--kt-ui-scrollbar-thumb-size);
    transition: width 200ms ease-in-out, right 200ms ease-in-out;
  }

  .q-scrollarea__thumb--h {
    height: var(--kt-ui-scrollbar-thumb-size);
    transition: height 200ms ease-in-out, right 200ms ease-in-out;
  }

  .q-scrollarea__thumb--v:hover,
  .q-scrollarea__thumb--h:hover,
  .q-scrollarea__bar--v:hover ~ .q-scrollarea__thumb--v,
  .q-scrollarea__bar--h:hover ~ .q-scrollarea__thumb--h {
    background-color: var(--kt-ui-scrollbar-thumb-color-hover);
  }

  .q-scrollarea__thumb--v:hover::after,
  .q-scrollarea__thumb--h:hover::after,
  .q-scrollarea__bar--v:hover ~ .q-scrollarea__thumb--v::after,
  .q-scrollarea__bar--h:hover ~ .q-scrollarea__thumb--h::after {
    opacity: 1;
  }

  .q-scrollarea__thumb--v:hover,
  .q-scrollarea__bar--v:hover ~ .q-scrollarea__thumb--v {
    width: var(--kt-ui-scrollbar-thumb-size-active);
  }

  .q-scrollarea__thumb--h:hover,
  .q-scrollarea__bar--h:hover ~ .q-scrollarea__thumb--h {
    height: var(--kt-ui-scrollbar-thumb-size-active);
  }
}
</style>
