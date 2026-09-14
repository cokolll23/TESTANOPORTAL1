<script lang="ts" setup>
import { KtSkeleton } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitleSkeleton } from '@/components/lk'
import { KtContactTableSkeleton } from '@/components/widgets/personal-info/components'
</script>

<template>
  <kt-widget-wrapper>
    <div class="kt-personal-contacts-skeleton">
      <header class="kt-personal-contacts-skeleton__header">
        <kt-widget-title-skeleton />
        <kt-skeleton type="circle" size="32px" />
      </header>

      <div class="kt-personal-contacts-skeleton__content">
        <kt-contact-table-skeleton />
      </div>
    </div>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-personal-contacts-skeleton {
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
