<script lang="ts" setup>
import { KtSkeleton, KtUserCardSkeleton } from '@/components/shared'
import { KtGratitudeSkeleton } from '@/components/widgets/gratitudes/components'
</script>

<template>
  <div class="kt-gratitude-post-skeleton">
    <div class="kt-gratitude-post-skeleton__left">
      <kt-user-card-skeleton show-avatar size="md" />
      <kt-gratitude-skeleton class="kt-gratitude-post-skeleton__gratitude" />
    </div>

    <div class="kt-gratitude-post-skeleton__right">
      <div class="kt-gratitude-post-skeleton__content">
        <kt-skeleton type="text" width="100%" height="14px" class="q-mb-xs" />
        <kt-skeleton type="text" width="50%" height="14px" />
      </div>
    </div>
  </div>
</template>

<style lang="scss">
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

  &__gratitude {
    transform: translateX(-8px);
  }

  &__content {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    gap: 4px;
  }
}
</style>
