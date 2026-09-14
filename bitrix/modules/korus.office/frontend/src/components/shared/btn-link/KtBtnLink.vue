<script lang="ts" setup>
import { h, computed, useAttrs, defineSlots, type VNode } from 'vue'
import { QBtn, QBtnProps, QBtnSlots } from 'quasar'
import { omit } from 'lodash-es'
import type { KtBtnLinkProps } from './types'
import { KtIcon } from '@/components/shared'

interface IProps extends KtBtnLinkProps, QBtnProps {}

interface ISlots extends QBtnSlots {
  icon:      () => VNode;
  iconRight: () => VNode;
}

const props = withDefaults(defineProps<IProps>(), {
  theme: 'primary',
  underline: false,
  unelevated: true,
  rounded: true,
  noCaps: true,
  iconFit: 'auto',
  iconRightFit: 'auto'
})
const propsBtn = computed(() => {
  return omit(props, ['label', 'theme', 'iconFit', 'iconRightFit'])
})

const slots = defineSlots<ISlots>()

const layoutClasses = computed(() => ([
  `kt-btn-link--${props.theme}-theme`,
  {
    'is-underline': props.underline
  }
]))

function render () {
  const attrs = useAttrs()

  return h(QBtn, {
    ...propsBtn.value,
    ...attrs,
    icon: undefined,
    iconRight: undefined,
    class: ['kt-btn', 'kt-btn-link', layoutClasses.value]
  }, {
    ...slots,
    default: () => {
      const content: VNode[] = []

      if (props.icon || typeof slots.icon === 'function') {
        content.push(
          typeof slots.icon === 'function'
            ? slots.icon()
            : h(KtIcon, { name: props.icon, fit: props.iconFit })
        )
      }

      if (props.label || typeof slots.default === 'function') {
        content.push(h('span', {
          class: 'kt-btn-link__text'
        }, typeof slots.default === 'function' ? slots.default() : props.label))
      }

      if (props.iconRight || typeof slots.iconRight === 'function') {
        content.push(
          typeof slots.iconRight === 'function'
            ? slots.iconRight()
            : h(KtIcon, { name: props.iconRight, fit: props.iconRightFit })
        )
      }

      return content
    }
  })
}
</script>

<template>
  <render />
</template>

<style lang="scss">
@use 'design';

.kt-btn-link {
  --kt-btn-height: auto;
  --kt-btn-min-width: initial;
  --kt-btn-padding: 0;
  --kt-btn-border-radius: 4px;
  --kt-btn-line-height: 1.28;

  --kt-btn-border-color: var(--kt-ui-transparent-01);
  --kt-btn-border-color-hover: var(--kt-ui-transparent-01);
  --kt-btn-border-color-active: var(--kt-ui-transparent-01);
  --kt-btn-border-color-disabled: var(--kt-ui-transparent-01);

  --kt-btn-bg-color: var(--kt-ui-transparent-01);
  --kt-btn-bg-color-hover: var(--kt-ui-transparent-01);
  --kt-btn-bg-color-active: var(--kt-ui-transparent-01);
  --kt-btn-bg-color-disabled: var(--kt-ui-transparent-01);

  &.is-underline &__text {
    border-bottom: 1px dashed currentColor;
  }

  &.q-btn--dense.q-btn--round {
    --kt-btn-min-width: initial;
    --kt-btn-height: initial;
  }

  .q-icon,
  .q-spinner {
    font-size: 18px;
  }
}
</style>
