<script lang="ts" setup>
import { h, defineModel } from 'vue'
import { extend } from 'quasar'
import type { SwitcherOption } from './types'
import type { KtBtnTheme } from '@/composables/shared'

import { KtBtn } from '@/components/shared/btn'

interface IProps {
  options:        SwitcherOption[];
  multiple:       boolean;
  btnTheme:       KtBtnTheme;
  btnActiveTheme: KtBtnTheme;
  bordered?:      boolean;
}

const props = withDefaults(defineProps<IProps>(), {
  multiple: false,
  btnTheme: 'ghost',
  btnActiveTheme: 'secondary'
})
const modelValue = defineModel()

function getBtnTheme (option: SwitcherOption) {
  return isBtnActive(option) ? props.btnActiveTheme : props.btnTheme
}

function isBtnActive (option: SwitcherOption) {
  const modelValueNormalized = Array.isArray(modelValue.value)
    ? modelValue.value
    : [modelValue.value]

  return modelValueNormalized.includes(option.value)
}

function updateModel (option: SwitcherOption) {
  if (props.multiple) {
    const newModelValue = Array.isArray(modelValue.value)
      ? extend(true, [], modelValue.value) as any[]
      : []
    const optionValueIndex = newModelValue.findIndex(item => {
      return item === option.value
    })

    if (optionValueIndex === -1) {
      newModelValue.push(option.value)
    }
    else {
      newModelValue.splice(optionValueIndex, 1)
    }

    modelValue.value = newModelValue
  }
  else {
    modelValue.value = option.value
  }
}

function render () {
  return h('div', {
    class: ['kt-btn-switcher', { 'is-bordered': props.bordered }]
  }, props.options.map(option => {
    const { value, ...btnProps } = option

    return h(KtBtn, {
      ...btnProps,
      key: value,
      theme: getBtnTheme(option),
      class: { 'is-active': isBtnActive(option) },
      onClick: () => {
        updateModel(option)
      }
    })
  }))
}
</script>

<template>
  <render />
</template>

<style lang="scss">
.kt-btn-switcher {
  display: inline-flex;
  align-items: center;

  &.is-bordered {
    padding: 8px;
    border: 1px solid #a8a8a8;
    border-radius: 16px;
  }

  .kt-btn {
    --kt-btn-margin-left: 8px;

    &.is-active {
      color: var(--_kt-btn-color-hover);
      background-color: var(--_kt-btn-bg-color-hover);
      cursor: default;
    }

    &.is-active::before {
      border-color: var(--_kt-btn-border-color-hover);
    }
  }
}
</style>
