<script lang="ts" setup>
import { KtGratitudeSkeleton } from '@/components/widgets/gratitudes/components'
</script>

<template>
  <div class="kt-gratitude-list-skeleton">
    <kt-gratitude-skeleton v-for="gratitude in 14" :key="gratitude" />
  </div>
</template>

<style lang="scss">
@use 'vars';

.kt-gratitude-list-skeleton {
  width: 100%;
  display: flex;
  flex-wrap: wrap;
  gap: var(--kt-gratitude-list-offset);
}
</style>
