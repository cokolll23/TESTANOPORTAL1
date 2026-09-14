<script lang="ts" setup>
import { KtSkeleton, KtTagSkeleton } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitleSkeleton } from '@/components/lk'
</script>

<template>
  <kt-widget-wrapper>
    <div class="kt-interests-skeleton">
      <header class="kt-interests-skeleton__header">
        <kt-widget-title-skeleton />
        <kt-skeleton type="circle" size="32px" />
      </header>

      <div class="kt-interests-skeleton__content">
        <div class="kt-interests-skeleton__tags">
          <kt-tag-skeleton v-for="tag in 5" :key="tag" />
        </div>
      </div>
    </div>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-interests-skeleton {
  &__header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: var(--kt-ui-offset-md);
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }

  &__tags {
    display: flex;
    flex-wrap: wrap;
    gap: var(--kt-ui-offset-md);
  }
}
</style>
