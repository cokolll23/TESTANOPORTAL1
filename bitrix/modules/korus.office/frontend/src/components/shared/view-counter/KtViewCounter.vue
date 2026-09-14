<script lang="ts" setup>
import { KtIcon } from '@/components/shared'

interface IProps {
  count: number;
}

const props = defineProps<IProps>()
</script>

<template>
  <div class="kt-view-counter">
    <kt-icon name="eye" class="kt-view-counter__icon" />
    <span class="kt-view-counter__text">{{ props.count }}</span>
  </div>
</template>

<style lang="scss">
@use 'vars';

.kt-view-counter {
  display: inline-flex;
  align-items: start;
  gap: 8px;

  &__icon {
    font-size: var(--_kt-view-counter-size);
  }

  &__text {
    line-height: var(--_kt-view-counter-size);
  }
}
</style>
