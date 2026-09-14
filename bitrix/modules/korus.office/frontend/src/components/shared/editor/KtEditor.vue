<script lang="ts" setup>
import { h, ref, computed, useAttrs, defineSlots } from 'vue'
import { QEditor, QEditorProps, QEditorSlots } from 'quasar'
import { omit } from 'lodash-es'

const props = defineProps<QEditorProps>()
const propsEditor = computed(() => {
  return omit(props, ['filled'])
})

const slots = defineSlots<QEditorSlots>()

const qEditorRef = ref<QEditor>()

defineExpose({
  qEditorRef
})

function render () {
  const attrs = useAttrs()

  return h(QEditor, {
    ...propsEditor.value,
    ...attrs,
    ref: qEditorRef,
    class: 'kt-editor'
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
  .kt-editor {
    border: none;
    border-radius: 16px;

    .q-editor__content {
      padding: 12px 16px;
      font-size: 14px;
      color: var(--q-app-grey-2);
      background-color: #f4f4f4;
      max-height: 110px;

      &::-webkit-scrollbar {
        width: 6px;
      }

      &::-webkit-scrollbar-thumb {
        background: rgb(224, 224, 224);
        border-radius: 8px;
      }

    }

    .q-editor__content:empty:not(:focus-visible)::before {
      font-size: 14px;
      opacity: 1;
      color: #a8a8a8;
    }
  }
}
</style>
