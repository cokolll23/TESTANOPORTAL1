<script lang="ts" setup>
import { defineModel } from 'vue'

import { KtPopupProxy } from '@/components/shared'
import { KtBadgesDetailSlider } from '@/components/lk/badges/components'

const modelValue = defineModel<boolean>({ required: true })
</script>

<template>
  <kt-popup-proxy v-model="modelValue"
                  anchor="top right"
                  self="center left"
                  :offset="[15, 0]"
                  :title-text="$t('badges.popup.title')"
                  seamless
                  max-width="480px"
                  class="kt-badges-detail-popup"
  >
    <kt-badges-detail-slider />
  </kt-popup-proxy>
</template>

<style lang="scss">
.kt-badges-detail-popup {
}
</style>
