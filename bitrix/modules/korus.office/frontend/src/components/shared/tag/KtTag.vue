<script lang="ts" setup>
import { h, computed, useAttrs, defineSlots, type VNode } from 'vue'
import { QChip, QChipProps, QChipSlots } from 'quasar'
import { omit } from 'lodash-es'
// import { getIconSource } from '@/components/shared/icon/getSource'
import type { ITagTheme } from '@/api/models/shared'

interface IProps extends QChipProps {
  theme?:     ITagTheme;
  inline?:    boolean;
  text?:      string;
  uppercase?: boolean;
  href?:      string;
}

const props = withDefaults(defineProps<IProps>(), {
  theme: 'default',
  inline: false,
  disable: false,
  modelValue: true,
  selected: null,
  clickable: false,
  iconRemove: 'close',
  uppercase: false
})
const propsChip = computed(() => {
  return omit(props, ['theme', 'inline', 'text', 'uppercase'])
})
const slots = defineSlots<QChipSlots>()

const theme = computed(() => {
  return `kt-tag--${props.theme}-theme`
})
const layoutClasses = computed(() => ([
  'kt-tag',
  theme.value,
  {
    'kt-tag--inline': props.inline,
    'text-uppercase': props.uppercase
  }
]))

function renderContent () {
  if (typeof slots.default === 'function') {
    return slots.default()
  }

  const content: (VNode | string)[] = []

  if (props.inline) {
    content.push(h('span', { class: 'kt-tag__circle' }))
  }

  if (typeof props.text !== 'undefined') {
    content.push(
      props.href
        ? h('a', { href: props.href, class: 'kt-tag__link' }, props.text)
        : props.text
    )
  }

  return content
}

function render () {
  const attrs = useAttrs()
  const content = renderContent()

  return h(QChip, {
    ...propsChip.value,
    ...attrs,
    iconRemove: 'none',
    class: layoutClasses.value
  }, content)
}
</script>

<template>
  <render />
</template>

<style lang="scss">
@use 'vars';
@use './design';

/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-tag {
    height: 24px;
    padding: 0 8px;
    font-size: 12px;
    letter-spacing: 0.32px;
    border-radius: 24px;
    color: var(--kt-tag-color);
    background-color: var(--kt-tag-bg);

    &.disabled {
      color: var(--kt-tag-color-disabled);
      background-color: var(--kt-tag-bg-disabled);
    }

    &--inline,
    &--inline.disabled {
      background-color: transparent;
    }

    &--inline {
      font-size: 14px;
    }

    &.q-chip--outline {
      transition-property: color, border-color, background-color;
      transition-duration: 160ms;
      transition-timing-function: linear;
    }

    &__circle {
      width: 10px;
      height: 10px;
      margin-right: 8px;
      border-radius: 50%;
      background-color: var(--kt-tag-bg);
    }

    &.disabled &__circle {
      background-color: var(--kt-tag-bg-disabled);
    }

    &__link {
      color: var(--kt-tag-color);
    }

    &.disabled &__link {
      color: var(--kt-tag-color-disabled);
    }

    .q-chip__icon--left {
      display: none;
    }

    .q-chip__icon--remove {
      width: 16px;
      height: 16px;
      margin: 0;
      border-radius: 50%;
      position: absolute;
      top: -4px;
      right: -8px;
      opacity: 1;
      background-color: var(--kt-tag-remove-btn-bg);
      transition-property: background-color, box-shadow;
      transition-duration: 160ms;
      transition-timing-function: linear;
      will-change: background-color, box-shadow;
    }

    .q-chip__icon--remove::before {
      content: '';
      mask-image: var(--kt-ui-icon-close);
      mask-repeat: no-repeat;
      mask-position: 50% 50%;
      mask-size: 50%;
      background-color: var(--kt-tag-remove-btn-color);
      transition: background-color 160ms linear;
    }

    &:not(.disabled) .q-chip__icon--remove:hover {
      background-color: var(--kt-tag-remove-btn-bg-hover);
    }

    &:not(.disabled) .q-chip__icon--remove:hover::before {
      background-color: var(--kt-tag-remove-btn-color-hover);
    }

    &:not(.disabled) .q-chip__icon--remove:focus-visible {
      box-shadow: 0 0 0 1px #7949f4;
    }

    &.disabled .q-chip__icon--remove {
      background-color: var(--kt-tag-remove-btn-bg-disabled);
    }

    //body.desktop &.q-chip--clickable:focus {
    //  box-shadow: none;
    //}
    //
    //body.desktop &.q-focus-helper,
    //body.desktop &.q-focusable:focus > .q-focus-helper,
    //body.desktop &.q-manual-focusable--focused > .q-focus-helper,
    //body.desktop &.q-hoverable:hover > .q-focus-helper {
    //  display: none;
    //}
  }

  &.desktop .kt-tag.q-chip--clickable:focus {
    box-shadow: none;
  }

  &.desktop .kt-tag.q-focus-helper,
  &.desktop .kt-tag.q-focusable:focus > .q-focus-helper,
  &.desktop .kt-tag.q-manual-focusable--focused > .q-focus-helper,
  &.desktop .kt-tag.q-hoverable:hover > .q-focus-helper {
    display: none;
  }
}
</style>
