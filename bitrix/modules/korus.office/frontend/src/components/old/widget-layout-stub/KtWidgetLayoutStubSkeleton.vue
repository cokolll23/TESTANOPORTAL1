<template>
  <div class="kt-widget-layout-stub-skeleton">
    <div class="kt-widget-layout-stub-skeleton__left">
      <q-skeleton type="circle" bordered />
    </div>

    <div class="kt-widget-layout-stub-skeleton__right">
      <q-skeleton type="rect" width="100%" height="40px" />
      <q-skeleton type="QBtn" width="160px" />
    </div>
  </div>
</template>

<style lang="scss">
.pgk {
  .kt-widget-layout-stub-skeleton {
    $width: 100%;

    width: $width;
    display: flex;
    align-items: center;

    @media screen and (max-width: $breakpoint-xs) {
      flex-wrap: wrap;
      justify-content: center;
    }

    &__left {
      .q-skeleton {
        $width: 100px;
        $height: 100px;

        width: $width;
        height: $height;
      }
    }

    &__right {
      flex-grow: 1;

      @media screen and (min-width: $breakpoint-xs) {
        $margin-left: 20px;

        margin-left: $margin-left;
      }

      @media screen and (max-width: $breakpoint-xs) {
        $width: 100%;
        $margin-top: 20px;

        width: $width;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-top: $margin-top;
      }
    }

    .q-skeleton--type-QBtn {
      $margin-top: 20px;

      margin-top: $margin-top;
    }
  }
}
</style>
