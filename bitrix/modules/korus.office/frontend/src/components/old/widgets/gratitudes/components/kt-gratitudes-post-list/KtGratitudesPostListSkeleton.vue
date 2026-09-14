<template>
  <ul class="kt-gratitudes-post-list-skeleton">
    <li v-for="gratitude in 5" :key="gratitude" class="kt-gratitudes-post-list-skeleton__item">
      <kt-gratitudes-post-item-skeleton />
    </li>
  </ul>

  <q-skeleton type="QBtn"
              width="100px"
              height="14px"
              class="kt-gratitudes-post-list-skeleton__show-more-btn"
  />
</template>

<script lang="ts" setup>
import { KtGratitudesPostItemSkeleton } from '../'
</script>

<style lang="scss">
.pgk {
  .kt-gratitudes-post-list-skeleton {
    $width: 100%;
    $margin-top: 25px;

    width: $width;
    margin-top: $margin-top;

    &__show-more-btn {
      $margin-top: 15px;

      margin-top: $margin-top;
    }
  }

  .kt-gratitudes-post-list-skeleton__item + .kt-gratitudes-post-list-skeleton__item {
    $margin-top: 14px;

    margin-top: $margin-top;
  }
}
</style>
