<template>
  <kt-widget-layout :header="false" :separator="false" class="kt-workplace">
    <div v-if="WORKPLACE.TITLE">
      <kt-widget-layout-title :title="$t('workplace.workplace')"/>
      <kt-link :href="WORKPLACE.URL" :text="WORKPLACE.TITLE"/>
    </div>
    <div v-if="BOOKING.length > 0" :class="WORKPLACE.TITLE ? 'booking_margin_top' : ''">
      <kt-widget-layout-title :title="$t('workplace.booking')"/>
      <div v-for="booking in BOOKING" :key="booking" class="booking_item">
        <kt-link :href="booking.URL" :text="booking.TITLE" />
        <kt-widget-layout-text :text="booking.DATE" />
      </div>
    </div>
  </kt-widget-layout>
</template>

<script lang="ts" setup>
import { storeToRefs } from 'pinia'
import { useWorkplaceStore } from 'stores/workplace'

import { KtWidgetLayout } from 'components/old/widget-layout'
import { KtLink } from 'components/old/link'
import { KtWidgetLayoutTitle } from 'components/old/widget-layout-title'
import { KtWidgetLayoutText } from 'components/old/widget-layout-text'

const { WORKPLACE, BOOKING } = storeToRefs(useWorkplaceStore())
</script>

<style lang="scss">
.kt-workplace {
  .booking_margin_top {
    margin-top: 20px;
  }

  .booking_item + .booking_item {
    margin-top: 10px;
  }
}
</style>
