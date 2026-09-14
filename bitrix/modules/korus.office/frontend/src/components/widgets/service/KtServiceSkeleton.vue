<script lang="ts" setup>
import { KtSkeleton } from '@/components/shared'
import { KtServiceTitleSkeleton } from '@/components/widgets/service/components'
</script>

<template>
  <div class="kt-service-skeleton">
    <header class="kt-service-skeleton__header">
      <kt-service-title-skeleton />
    </header>

    <div class="kt-service-skeleton__content">
      <kt-skeleton type="text" width="100%" height="16px" />
      <kt-skeleton type="text" width="100%" height="16px" />
      <kt-skeleton type="text" width="100%" height="16px" />
    </div>

    <footer class="kt-service-skeleton__actions">
      <kt-skeleton type="QBtn" width="150px" class="kt-service-skeleton__action" />
      <kt-skeleton type="QBtn" width="100px" class="kt-service-skeleton__action" />
    </footer>
  </div>
</template>

<style lang="scss">
.kt-service-skeleton {
  display: flex;
  flex-direction: column;
  gap: var(--kt-service-offset);
  padding: var(--kt-service-padding);
  border-radius: var(--kt-service-border-radius);
  background-color: var(--kt-service-bg);

  &__header {
    gap: var(--kt-ui-offset-lg);
  }

  &__content {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  &__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: auto;
  }

  &__action {
    --kt-btn-margin-left: 0;
  }
}
</style>
