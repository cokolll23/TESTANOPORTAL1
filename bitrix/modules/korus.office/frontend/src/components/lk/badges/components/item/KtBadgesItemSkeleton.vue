<script lang="ts" setup>
import { KtSkeleton } from '@/components/shared'
</script>

<template>
  <kt-skeleton type="circle" class="kt-badges-item-skeleton" />
</template>

<style lang="scss">
.kt-badges-item-skeleton {
  width: var(--kt-badges-item-size);
  height: var(--kt-badges-item-size);
}
</style>
