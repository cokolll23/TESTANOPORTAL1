<script lang="ts" setup>
import { KtSkeleton } from '@/components/shared'
import { KtWidgetWrapper } from '@/components/lk'
</script>

<template>
  <kt-widget-wrapper>
    <div class="kt-personal-common-skeleton">
      <div class="kt-personal-common-skeleton__top">
        <kt-skeleton type="text" width="250px" height="20px" />

        <div class="kt-personal-common-skeleton__status">
          <kt-skeleton type="circle" size="32px" />
          <kt-skeleton type="Qbtn" width="100px" />
        </div>
      </div>

      <div class="kt-personal-common-skeleton__bottom">
        <kt-skeleton type="text" width="150px" height="16px" />
      </div>
    </div>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-personal-common-skeleton {
  display: flex;
  flex-wrap: wrap;
  gap: var(--kt-ui-offset-md);

  &__top,
  &__bottom {
    width: 100%;
  }

  &__top {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: var(--kt-ui-offset-lg);
  }

  &__status {
    display: inline-flex;
    align-items: center;
    gap: 10px;
  }
}
</style>
