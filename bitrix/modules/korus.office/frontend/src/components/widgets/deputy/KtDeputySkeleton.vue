<script lang="ts" setup>
import { KtSkeleton, KtUserCardSkeleton } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitleSkeleton } from '@/components/lk'
</script>

<template>
  <kt-widget-wrapper>
    <div class="kt-deputy-skeleton">
      <header class="kt-deputy-skeleton__header">
        <kt-widget-title-skeleton />
      </header>

      <div class="kt-deputy-skeleton__content">
        <div class="kt-deputy-skeleton-block">
          <kt-skeleton type="text" width="70%" />
          <kt-user-card-skeleton show-avatar show-name size="sm">
            <template #undername>
              <kt-skeleton type="text" width="180px" height="16px" />
            </template>
          </kt-user-card-skeleton>
        </div>
      </div>
    </div>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-deputy-skeleton {
  &__header {
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }

  &-block {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
}
</style>
