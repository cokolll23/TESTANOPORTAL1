<script lang="ts" setup>
import { KtSkeleton } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitleSkeleton } from '@/components/lk'
</script>

<template>
  <kt-widget-wrapper>
    <div class="kt-shop-skeleton">
      <header class="kt-shop-skeleton__header">
        <kt-widget-title-skeleton />
      </header>

      <div class="kt-shop-skeleton__content">
        <kt-skeleton type="text" width="100%" height="16px" />
        <kt-skeleton type="text" width="50%" height="16px" />

        <div class="kt-shop-skeleton__balance">
          <kt-skeleton type="circle" class="kt-shop-skeleton__balance-icon" />
          <kt-skeleton type="text" width="80px" height="20px" />
        </div>
      </div>

      <footer class="kt-shop-skeleton__footer">
        <kt-skeleton type="QBtn" width="100px" />
      </footer>
    </div>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-shop-skeleton {
  &__header {
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }

  &__content {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  &__balance {
    display: flex;
    align-items: center;
    gap: 12px;

    &-icon {
      width: var(--kt-shop-coin-size);
      height: var(--kt-shop-coin-size);
      border-radius: 50%;
    }
  }

  &__footer {
    display: flex;
    justify-content: center;
    margin-top: var(--kt-widget-wrapper-footer-offset);
  }
}
</style>
