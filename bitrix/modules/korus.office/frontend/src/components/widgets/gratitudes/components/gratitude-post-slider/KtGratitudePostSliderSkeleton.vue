<script lang="ts" setup>
import { KtSkeleton } from '@/components/shared'
import { KtGratitudePostSkeleton } from '@/components/widgets/gratitudes/components'
</script>

<template>
  <div class="kt-gratitude-post-slider-skeleton-wr">
    <div class="kt-gratitude-post-slider-skeleton__nav">
      <kt-skeleton type="circle" size="32px" class="kt-gratitude-post-slider-skeleton__btn" />
      <kt-skeleton type="circle" size="32px" class="kt-gratitude-post-slider-skeleton__btn" />
    </div>

    <div class="kt-gratitude-post-slider-skeleton__content">
      <kt-gratitude-post-skeleton v-for="post in 3" :key="post" />
    </div>
  </div>
</template>

<style lang="scss">
.kt-gratitude-post-slider-skeleton {
  &__nav {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
  }

  &__btn {
    margin-bottom: var(--kt-gratitude-post-slider-gap);
  }

  &__content {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }
}
</style>
