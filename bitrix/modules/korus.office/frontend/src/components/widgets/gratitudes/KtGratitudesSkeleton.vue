<script lang="ts" setup>
import { KtWidgetWrapper, KtWidgetTitleSkeleton } from '@/components/lk'
import { KtGratitudeListSkeleton, KtGratitudePostSliderSkeleton } from './components'
</script>

<template>
  <kt-widget-wrapper>
    <div class="kt-gratitude-skeleton">
      <header class="kt-gratitude-skeleton__header">
        <kt-widget-title-skeleton />
      </header>

      <div class="kt-gratitude-skeleton__content">
        <kt-gratitude-list-skeleton />
        <kt-gratitude-post-slider-skeleton class="kt-gratitude-skeleton__post-slider" />
      </div>
    </div>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-gratitude-skeleton {
  &__header {
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }

  &__post-slider {
    margin-top: var(--kt-ui-offset-lg);
  }
}
</style>
