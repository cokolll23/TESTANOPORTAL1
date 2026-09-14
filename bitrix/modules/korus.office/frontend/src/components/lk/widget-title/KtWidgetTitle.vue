<script lang="ts" setup>
import { KtTitle, KtBtn, KtIcon } from '@/components/shared'

interface IProps {
  text:  string;
  href?: string;
}

const props = defineProps<IProps>()
</script>

<template>
  <kt-title :level="2" class="kt-widget-title">
    <span class="kt-widget-title__text">{{ props.text }}</span>
    <kt-btn v-if="props.href"
            :href="props.href"
            theme="ghost"
            icon="chevron-right"
            round
            dense
            class="kt-widget-title__link"
    />
  </kt-title>
</template>

<style lang="scss">
@use 'vars';

.kt-widget-title {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: var(--kt-widget-title-font-weight);

  &__link {
    font-size: var(--kt-widget-title-link-size);
    color: var(--kt-widget-title-link-color);

    &:hover {
      color: var(--kt-widget-title-link-color-hover);
    }

    &:active {
      color: var(--kt-widget-title-link-color-active);
    }
  }

  &__link .kt-icon {
    font-size: 20px;
  }
}
</style>
