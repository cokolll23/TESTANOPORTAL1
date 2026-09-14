<template>
  <kt-widget-layout :title="$t('shop.title')" class="kt-shop">
    <kt-widget-layout-text :text="$t('shop.text')" color="app-grey-5" />

    <div class="kt-shop__info">
      <kt-icon name="kt:gift" color="white" background-color="app-deep-orange-1" icon-size="20px" />
      <span class="kt-shop__bonuses">{{ ACCOUNT.BALANCE }}</span>

      <kt-button :href="URLS.SHOP" outline class="q-ml-auto">
        {{ $t('shop.btn') }}
      </kt-button>
    </div>
  </kt-widget-layout>
</template>

<script lang="ts" setup>
import { storeToRefs } from 'pinia'
import { useShopStore } from 'stores/shop'

import { KtWidgetLayout } from 'components/old/widget-layout'
import { KtWidgetLayoutText } from 'components/old/widget-layout-text'
import { KtIcon } from 'components/old/icon'
import { KtButton } from 'components/old/button'

const { ACCOUNT, URLS } = storeToRefs(useShopStore())
</script>

<style lang="scss">
.pgk {
  .kt-shop {
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
      $font-weight: 600;
      $line-height: 18px;
      $color: var(--q-primary);

      margin-left: $margin-left;
      font-weight: $font-weight;
      line-height: $line-height;
      color: $color;

      @media screen and (min-width: $breakpoint-sm) {
        $margin-left: 5px;
        $font-size: 14px;

        margin-left: $margin-left;
        font-size: $font-size;
      }

      @media screen and (min-width: $breakpoint-md) {
        $font-size: 16px;

        font-size: $font-size;
      }

      @media screen and (min-width: $breakpoint-lg) {
        $margin-left: 10px;
        $font-size: 20px;

        margin-left: $margin-left;
        font-size: $font-size;
      }
    }
  }
}
</style>
