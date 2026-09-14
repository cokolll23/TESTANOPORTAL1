<script lang="ts" setup>
import { KtSkeleton } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitleSkeleton } from '@/components/lk'
</script>

<template>
  <kt-widget-wrapper>
    <div class="kt-about-me-skeleton">
      <header class="kt-about-me-skeleton__header">
        <kt-widget-title-skeleton />
        <kt-skeleton type="circle" size="32px" />
      </header>

      <div class="kt-about-me-skeleton__content">
        <kt-skeleton type="rect" width="100%" height="100px" />
      </div>
    </div>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-about-me-skeleton {
  &__header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: var(--kt-ui-offset-md);
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }
}
</style>
