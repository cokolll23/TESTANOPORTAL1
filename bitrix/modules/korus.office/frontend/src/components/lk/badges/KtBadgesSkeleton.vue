<script lang="ts" setup>
import { KtBadgesItemSkeleton } from '@/components/lk/badges/components'
</script>

<template>
  <div class="kt-badges-skeleton">
    <div v-for="badge in 5" :key="badge" class="kt-badges-skeleton__item-wrapper">
      <kt-badges-item-skeleton />
    </div>
  </div>
</template>

<style lang="scss">
.kt-badges-skeleton {
  display: flex;
  flex-direction: column;
  align-items: center;

  &__item-wrapper + &__item-wrapper {
    margin-top: -8px;
    margin-left: 0;
  }
}
</style>
