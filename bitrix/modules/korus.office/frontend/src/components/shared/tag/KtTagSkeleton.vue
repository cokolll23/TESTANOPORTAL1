<script lang="ts" setup>
import { KtSkeleton } from '@/components/shared'
</script>

<template>
  <kt-skeleton type="QChip" class="kt-tag-skeleton" />
</template>

<style lang="scss">
/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-tag-skeleton {
    width: 100px;
    height: 24px;
    border-radius: 24px;
  }
}
</style>
