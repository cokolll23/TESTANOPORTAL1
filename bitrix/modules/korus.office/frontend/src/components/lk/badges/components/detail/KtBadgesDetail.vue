<script lang="ts" setup>
import { IBadge } from 'stores/badges'

import { KtTitle, KtTextClamp } from '@/components/shared'
import { KtBadgesItem } from '@/components/lk/badges/components'

interface IProps {
  badge: IBadge;
}

const props = defineProps<IProps>()
</script>

<template>
  <article class="kt-badges-detail">
    <div class="kt-badges-detail__left">
      <kt-badges-item :image="badge.image" :color="badge.color" class="kt-badges-detail__image" />
    </div>

    <div class="kt-badges-detail__right">
      <kt-title :level="3" class="kt-badges-detail__name">
        <kt-text-clamp :content="badge.name" :lines="1" />
      </kt-title>

      <kt-text-clamp :content="badge.description" html-mode class="kt-badges-detail__description" />
      <span class="kt-badges-detail__date">{{ badge.date }}</span>
    </div>
  </article>
</template>

<style lang="scss">
@use 'vars';

.kt-badges-detail {
  display: flex;
  align-items: center;
  width: 100%;
  gap: 8px;
  padding: var(--kt-badges-detail-padding);
  font-size: 14px;
  border-radius: var(--kt-badges-detail-border-radius);
  background-color: var(--kt-badges-detail-bg);

  &__left {
    flex-shrink: 0;
  }

  &__image {
    border: 2px solid var(--kt-badges-detail-image-border-color);
  }

  &__right {
    display: flex;
    flex-direction: column;
    gap: 4px;
    margin: 0;
  }

  &__name,
  &__description {
    color: var(--kt-badges-detail-color);
  }

  &__name {
    --kt-title-font-size: 16px;
    --kt-title-line-height: 1.375;

    font-weight: 700;
  }

  &__date {
    color: var(--kt-badges-detail-date-color);
  }
}
</style>
