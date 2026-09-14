<template>
  <kt-widget-layout-skeleton :title="$t('shop.title')" class="kt-shop-skeleton">
    <q-skeleton type="rect" width="100%" height="60px" />

    <div class="kt-shop-skeleton__info">
      <kt-icon-skeleton />
      <span class="kt-shop__bonuses">
        <q-skeleton type="text" width="60px" height="20px" />
      </span>

      <q-skeleton type="QBtn" width="100px" class="q-ml-auto" />
    </div>
  </kt-widget-layout-skeleton>
</template>

<script lang="ts" setup>
import { KtWidgetLayoutSkeleton } from 'components/old/widget-layout'
import { KtIconSkeleton } from 'components/old/icon'
</script>

<style lang="scss">
.pgk {
  .kt-shop-skeleton {
    &__info {
      $width: 100%;
      $margin-top: 20px;

      width: $width;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      margin-top: $margin-top;
    }

    &__bonuses {
      $margin-left: 10px;

      margin-left: $margin-left;
    }
  }
}
</style>
