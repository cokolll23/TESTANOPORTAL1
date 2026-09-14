<template>
  <kt-widget-layout-skeleton :title="$t('shop.title')" class="kt-shop-skeleton">
    <q-skeleton height="60px" type="rect" width="100%" />

    <div class="kt-shop-skeleton__info">
      <kt-icon-skeleton />
      <span class="kt-shop__bonuses">
        <q-skeleton height="20px" type="text" width="60px" />
      </span>

      <q-skeleton class="q-ml-auto" type="QBtn" width="100px" />
    </div>
  </kt-widget-layout-skeleton>
</template>

<script lang="ts" setup>
import { KtWidgetLayoutSkeleton } from 'components/old/widget-layout'
import { KtIconSkeleton } from 'components/old/icon'
</script>

<style lang="scss">
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
</style>
