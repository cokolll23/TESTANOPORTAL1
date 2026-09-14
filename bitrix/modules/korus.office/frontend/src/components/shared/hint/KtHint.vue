<script lang="ts" setup>
import { computed } from 'vue'
import { QTooltipProps } from 'quasar'
import { omit } from 'lodash-es'
import { KtTooltip } from '@/components/shared'

interface IProps extends QTooltipProps {
  content?:     string;
  placeholder?: string;
}

const props = withDefaults(defineProps<IProps>(), {
  target: true,
  delay: 0,
  hideDelay: 0,
  maxHeight: null,
  maxWidth: null,
  modelValue: undefined,
  anchor: 'bottom middle',
  self: 'top middle',
  offset: () => ([14, 14]),
  transitionShow: 'jump-down',
  transitionHide: 'jump-up',
  transitionDuration: 300
})
const propsTooltip = computed(() => {
  return omit(props, ['content', 'placeholder'])
})

const shouldShowPlaceholder = computed(() => {
  return 'placeholder' in props && 'content' in props && !props.content
})
</script>

<template>
  <div class="kt-hint">
    <template v-if="shouldShowPlaceholder">{{ props.placeholder }}</template>

    <template v-else>
      <a class="kt-link">Подробнее</a>

      <kt-tooltip v-bind="propsTooltip">
        <div v-if="props.content" v-html="props.content"></div>
        <slot v-else></slot>
      </kt-tooltip>
    </template>
  </div>
</template>

<style lang="scss">
.kt-hint {
}
</style>
