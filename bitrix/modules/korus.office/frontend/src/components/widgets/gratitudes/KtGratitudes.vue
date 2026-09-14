<script lang="ts" setup>
import { storeToRefs } from 'pinia'
import { useGratitudesStore } from '@/stores/gratitudes'
import { usePermissionsStore } from '@/stores/permissions'
import { useGratitudes } from '@/composables/gratitudes/useGratitudes'

import { KtBtn } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitle } from '@/components/lk'
import { KtGratitudeList, KtGratitudePostSlider } from './components'

const { IS_OWN_PROFILE } = storeToRefs(usePermissionsStore())
const { hasPosts } = storeToRefs(useGratitudesStore())
const { openGratitudePage } = useGratitudes()
</script>

<template>
  <kt-widget-wrapper>
    <article class="kt-gratitudes">
      <header class="kt-gratitudes__header">
        <kt-widget-title :text="$t('gratitudes.title')" />
      </header>

      <div class="kt-gratitudes__content">
        <kt-gratitude-list />
        <kt-gratitude-post-slider v-if="hasPosts" class="kt-gratitudes__post-slider" />
      </div>

      <footer v-if="!IS_OWN_PROFILE" class="kt-gratitudes__footer">
        <kt-btn theme="primary" :label="$t('gratitudes.thanksBtn')" @click="openGratitudePage()" />
      </footer>
    </article>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-gratitudes {
  &__header {
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }

  &__post-slider {
    margin-top: var(--kt-ui-offset-lg);
  }

  &__footer {
    display: flex;
    justify-content: center;
    margin-top: var(--kt-widget-wrapper-footer-offset);
  }
}
</style>
