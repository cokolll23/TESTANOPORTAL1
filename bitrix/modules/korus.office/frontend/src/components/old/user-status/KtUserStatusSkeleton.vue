<template>
  <div class="kt-user-status-skeleton">
    <q-skeleton type="circle" size="10px" />
    <q-skeleton type="text"
                width="30px"
                height="10px"
                class="kt-user-status-skeleton__text"
    />
  </div>
</template>

<style lang="scss">
.pgk {
  .kt-user-status-skeleton {
    display: inline-flex;
    align-items: center;

    &__text {
      $margin-left: 5px;

      margin-left: $margin-left;
    }
  }
}
</style>
