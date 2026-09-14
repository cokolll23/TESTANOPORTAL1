<script lang="ts" setup>
import { KtSkeleton, KtUserCardSkeleton } from '@/components/shared'
</script>

<template>
  <div class="kt-structure-user-list-skeleton">
    <div class="kt-structure-user-list-skeleton__label">
      <kt-skeleton type="text" width="150px" height="16px" />
    </div>

    <div class="kt-structure-user-list-skeleton__users">
      <kt-user-card-skeleton show-avatar show-name size="sm">
        <template #undername>
          <kt-skeleton type="text" width="180px" height="16px" />
        </template>
      </kt-user-card-skeleton>
    </div>
  </div>
</template>

<style lang="scss">
.kt-structure-user-list-skeleton {
  &__label {
    margin-bottom: var(--kt-ui-offset-lg);
  }

  &__users {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
  }
}
</style>
