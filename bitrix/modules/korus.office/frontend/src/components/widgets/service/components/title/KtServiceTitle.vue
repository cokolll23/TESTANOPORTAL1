<script lang="ts" setup>
import { KtTitle, KtBtn, KtIcon } from '@/components/shared'

interface IProps {
  text:  string;
  href?: string;
}

const props = defineProps<IProps>()
</script>

<template>
  <kt-title :level="3" class="kt-service-title">
    <span class="kt-service-title__text">{{ props.text }}</span>
    <kt-btn v-if="props.href"
            :href="props.href"
            icon="chevron-right"
            theme="ghost"
            round
            dense
            class="kt-service-title__link"
    />
  </kt-title>
</template>

<style lang="scss">
@use 'vars';

.kt-service-title {
  --kt-title-font-size: var(--kt-service-title-font-size);

  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: var(--kt-service-title-font-weight);

  &__link {
    font-size: var(--kt-service-title-link-size);
    color: var(--kt-service-title-link-color);

    &:hover {
      color: var(--kt-service-title-link-color-hover);
    }

    &:active {
      color: var(--kt-service-title-link-color-active);
    }
  }

  &__link .kt-icon {
    font-size: 20px;
  }
}
</style>
