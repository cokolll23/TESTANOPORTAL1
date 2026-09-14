<template>
  <div class="kt-pagination-skeleton">
    <q-skeleton type="QBtn" class="kt-pagination-skeleton__prev-btn" />
    <q-skeleton type="rect" width="60px" height="24px" />
    <q-skeleton type="QBtn" class="kt-pagination-skeleton__next-btn" />
  </div>
</template>

<style lang="scss">
.pgk {
  .kt-pagination-skeleton {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    &__prev-btn,
    &__next-btn {
      $size: 24px;

      min-height: $size;
      width: $size;
      height: $size;
    }

    &__prev-btn {
      $margin-right: 10px;

      margin-right: $margin-right;
    }

    &__next-btn {
      $margin-left: 10px;

      margin-left: $margin-left;
    }
  }
}
</style>
