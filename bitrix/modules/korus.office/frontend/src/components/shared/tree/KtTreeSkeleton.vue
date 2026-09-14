<script lang="ts" setup>
import { KtSkeleton } from '@/components/shared'
</script>

<template>
  <div class="kt-tree-skeleton">
    <kt-skeleton v-for="(level, index) in 4"
                 :key="level"
                 :style="{
                   'margin-left': `${index * 20}px`
                 }"
                 type="text"
                 width="200px"
                 height="16px"
    />
  </div>
</template>

<style lang="scss">
/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-tree-skeleton {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
}
</style>
