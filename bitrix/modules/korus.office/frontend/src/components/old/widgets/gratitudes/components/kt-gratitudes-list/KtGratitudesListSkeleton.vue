<template>
  <ul class="kt-gratitudes-list-skeleton">
    <li v-for="gratitude in 14" :key="gratitude" class="kt-gratitudes-list-skeleton__item">
      <kt-gratitudes-item-skeleton />
    </li>
  </ul>
</template>

<script lang="ts" setup>
import { KtGratitudesItemSkeleton } from '../'
</script>

<style lang="scss">
.pgk {
  .kt-gratitudes {
    $offset-top: 6px;
    $offset-left: 5px;

    &-list-skeleton {
      $width: 100%;
      $margin: #{$offset-top * -1} 0 0 #{$offset-left * -1};

      width: $width;
      display: flex;
      flex-wrap: wrap;
      margin: $margin;

      &__item {
        $margin: $offset-top $offset-left;

        margin: $margin;
      }
    }
  }
}
</style>
