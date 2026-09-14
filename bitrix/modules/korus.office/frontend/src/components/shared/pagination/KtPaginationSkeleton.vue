<script lang="ts" setup>
import { KtSkeleton } from '@/components/shared'
</script>

<template>
  <div class="kt-pagination-skeleton">
    <div class="kt-pagination-skeleton__numbers">
      <kt-skeleton v-for="number in 7" :key="number" type="circle" size="32px" />
    </div>

    <div class="kt-pagination-skeleton__page-size">
      <kt-skeleton type="text" width="90px" height="16px" />
      <kt-skeleton type="rect" width="40px" height="32px" />
    </div>
  </div>
</template>

<style lang="scss">
/**
  * TODO:REMOVE класс-завязку на боди
 */

body:not(.pgk) {
  .kt-pagination-skeleton {
    display: flex;
    flex-wrap: wrap;

    &__numbers {
      display: flex;
      align-items: center;
      gap: 4px;
    }

    &__page-size {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: var(--kt-ui-offset-md);
      margin-left: auto;
    }
  }
}
</style>
