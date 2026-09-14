<script lang="ts" setup>
import { computed } from 'vue'
import { KtIcon } from '@/components/shared'

interface IProps {
  href:      string;
  count:     number;
  readonly?: boolean;
}

const props = withDefaults(defineProps<IProps>(), {
  readonly: false
})

const layoutClasses = computed(() => ({
  'is-readonly': props.readonly
}))
const href = computed(() => {
  if (props.readonly) {
    return null
  }

  return props.href
})
</script>

<template>
  <div class="kt-comment-counter" :class="layoutClasses">
    <a :href="href" class="kt-comment-counter__link">
      <kt-icon name="chat" class="kt-comment-counter__icon" />
    </a>
    <span class="kt-comment-counter__text">{{ props.count }}</span>
  </div>
</template>

<style lang="scss">
@use 'vars';

.kt-comment-counter {
  display: inline-flex;
  align-items: start;
  gap: 8px;

  &__link,
  &.is-readonly &__link {
    color: var(--_kt-comment-counter-color);
  }

  &__link:not([href]) {
    cursor: default;
  }

  &:not(.is-readonly) &__link:is(:hover, :focus-visible) {
    color: var(--_kt-comment-counter-color-active);
  }

  &__icon {
    font-size: var(--_kt-comment-counter-size);
  }
}
</style>
