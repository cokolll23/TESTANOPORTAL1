<script lang="ts" setup>
import { storeToRefs } from 'pinia'
import { useShopStore } from '@/stores/shop'

import { KtBtn, KtImg } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitle, KtWidgetStub } from '@/components/lk'

import coinImage from '@/assets/widgets/shop/coin.png'

const { ACCOUNT, URLS } = storeToRefs(useShopStore())
</script>

<template>
  <kt-widget-wrapper>
    <article class="kt-shop">
      <header class="kt-shop__header">
        <kt-widget-title :href="URLS.SHOP" :text="$t('shop.title')" />
      </header>

      <div class="kt-shop__content">
        <template v-if="parseInt(ACCOUNT.BALANCE) > 0">
          <p class="kt-shop__text">{{ $t('shop.text') }}</p>
          <div class="kt-shop__balance">
            <kt-img :src="coinImage" alt="" ratio="1" class="kt-shop__balance-icon" />
            <span class="kt-shop__balance-text">{{ ACCOUNT.BALANCE }}</span>
          </div>
        </template>

        <kt-widget-stub v-else :text="$t('shop.stubText')" class="kt-shop__stub">
          <template #icon>
            <kt-img :src="coinImage" alt="" ratio="1" />
          </template>
        </kt-widget-stub>
      </div>

      <footer class="kt-shop__footer">
        <kt-btn theme="primary" :href="URLS.SHOP" :label="$t('shop.btn')" />
      </footer>
    </article>
  </kt-widget-wrapper>
</template>

<style lang="scss">
@use 'vars';

.kt-shop {
  &__header {
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }

  &__content {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  &__balance {
    display: flex;
    align-items: center;
    gap: 12px;
    font-family: var(--ui-font-family-secondary);
    font-size: 20px;
    font-weight: 500;

    &-icon {
      width: var(--kt-shop-coin-size);
      height: var(--kt-shop-coin-size);
      border-radius: 50%;
    }
  }

  &__footer {
    margin-top: var(--kt-widget-wrapper-footer-offset);
    text-align: center;
  }

  &__stub {
    .kt-img {
      width: var(--kt-shop-stub-coin-size);
      height: var(--kt-shop-stub-coin-size);
      flex-shrink: 0;
    }
  }
}
</style>
