<template>
  <article class="kt-badges-detail">
    <div class="kt-badges-detail__left">
      <kt-badges-item :image="props.badge.image"
                      :color="props.badge.color"
                      class="kt-badges-detail__image"
      />
    </div>

    <div class="kt-badges-detail__right">
      <h3 class="kt-badges-detail__name">{{ props.badge.name }}</h3>
      <p class="kt-badges-detail__description" v-html="props.badge.description"></p>
      <span class="kt-badges-detail__date">{{ props.badge.date }}</span>
    </div>
  </article>
</template>

<script lang="ts" setup>
import { IBadge } from 'stores/badges'
import { KtBadgesItem } from '../badges-item'

interface IProps {
  badge: IBadge;
}

const props = defineProps<IProps>()
</script>

<style lang="scss">
.pgk {
  .kt-badges-detail {
    $width: 100%;
    $gap: 20px;
    $padding: 20px;
    $font-size: 14px;
    $line-height: 19px;
    $border: 1px solid var(--q-app-grey-8);
    $border-radius: 6px;

    display: flex;
    align-items: flex-start;
    width: $width;
    gap: $gap;
    padding: $padding;
    font-size: $font-size;
    line-height: $line-height;
    border: $border;
    border-radius: $border-radius;

    @media screen and (max-width: $breakpoint-sm) {
      $width: auto;

      flex-wrap: wrap;
      width: $width;
    }

    &__left {
      flex-shrink: 0;
    }

    &__image {
      $filter: drop-shadow(0px 4px 4px rgba(0, 0, 0, 0.25));

      filter: $filter;
    }

    &__right {
      $gap: 4px;
      $margin: 0;

      display: flex;
      flex-direction: column;
      gap: $gap;
      margin: $margin;
    }

    &__name {
      $font-size: 16px;
      $line-height: 22px;
      $font-weight: 600;
      $color: var(--q-app-grey-2);

      font-size: $font-size;
      line-height: $line-height;
      font-weight: $font-weight;
      color: $color;
    }

    &__description,
    &__date {
      $color: var(--q-app-grey-4);

      color: $color;
    }
  }
}
</style>
