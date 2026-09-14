<script lang="ts" setup>
import { ref, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useQuasar, uid } from 'quasar'
import { Navigation, Grid } from 'swiper/modules'
import { useBadgesStore } from '@/stores/badges'

import { KtBtn } from '@/components/shared'
import { KtBadgesDetail } from '@/components/lk/badges/components'
import { Swiper as VueSwiper, SwiperSlide } from 'swiper/vue'

import 'swiper/scss'
import 'swiper/scss/navigation'
import 'swiper/scss/grid'

const $q = useQuasar()
const { badges } = storeToRefs(useBadgesStore())

const modules = [Navigation, Grid]
const swiperNextBtnId = uid()
const swiperPrevBtnId = uid()

const badgeMaxHeight = ref('109px')
const badgesVisibleCount = computed(() => {
  return Math.min(Math.floor($q.screen.height / parseInt(badgeMaxHeight.value)), 4)
})
</script>

<template>
  <vue-swiper :slides-per-view="1"
              :space-between="8"
              :modules="modules"
              :grid="{
                rows: 4,
                fill: 'column'
              }"
              :navigation="{
                prevEl: `[data-btn-prev='${swiperPrevBtnId}']`,
                nextEl: `[data-btn-next='${swiperNextBtnId}']`
              }"
              class="kt-badges-detail-slider"
  >
    <swiper-slide v-for="badge in badges" :key="badge.name">
      <kt-badges-detail :badge="badge" />
    </swiper-slide>
  </vue-swiper>

  <div class="kt-badges-detail-slider__nav">
    <kt-btn theme="tertiary"
            :data-btn-prev="swiperPrevBtnId"
            icon="chevron-left"
            round
            dense
            class="kt-badges-detail-slider__btn"
    />

    <kt-btn theme="tertiary"
            :data-btn-next="swiperNextBtnId"
            icon="chevron-right"
            round
            dense
            class="kt-badges-detail-slider__btn"
    />
  </div>
</template>

<style lang="scss">
.kt-badges-detail-slider {
  .swiper-wrapper {
    max-height: calc(v-bind('badgeMaxHeight') * v-bind('badgesVisibleCount'));
  }

  &__nav {
    text-align: right;
  }

  &__btn {
    margin-top: 16px;
  }
}
</style>
