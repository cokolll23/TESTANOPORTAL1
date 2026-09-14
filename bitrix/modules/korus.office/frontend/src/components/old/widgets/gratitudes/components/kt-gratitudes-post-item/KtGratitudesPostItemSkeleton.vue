<template>
  <div class="kt-gratitude-post-skeleton">
    <div class="kt-gratitude-post-skeleton__left">
      <kt-user-card-skeleton size="md" hide-content />
      <kt-icon-skeleton size="sm" />
    </div>

    <div class="kt-gratitude-post-skeleton__right">
      <div class="kt-gratitude-post-skeleton__content">
        <q-skeleton type="text" width="100%" height="14px" class="q-mb-xs" />
        <q-skeleton type="text" width="50%" height="14px" />
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { KtIconSkeleton } from 'components/old/icon'
import { KtUserCardSkeleton } from 'components/old/user-card'
</script>

<style lang="scss">
.pgk {
  .kt-gratitude-post-skeleton {
    display: flex;
    align-items: flex-start;

    &__left {
      display: flex;
      align-items: center;
    }

    &__right {
      overflow: hidden;
      flex-grow: 1;
    }

    .q-avatar,
    .kt-gratitudes-item {
      position: relative;
    }

    .kt-icon-skeleton-wrapper {
      $transform: translateX(-12px);
      $z-index: 5;

      transform: $transform;
      z-index: $z-index;
    }

    &__content {
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }
  }
}
</style>
