<script lang="ts" setup>
import { ref } from 'vue'
import { uid } from 'quasar'
import { Swiper } from 'swiper'
import { Navigation, Pagination, Grid } from 'swiper/modules'
import { useGratitudesStore } from '@/stores/gratitudes'
import { useGratitudePosts } from '@/composables/gratitudes/useGratitudePosts'

import { KtBtn } from '@/components/shared'
import { KtGratitudePost } from '@/components/widgets/gratitudes/components'
import { Swiper as VueSwiper, SwiperSlide } from 'swiper/vue'

import 'swiper/scss'
import 'swiper/scss/navigation'
import 'swiper/scss/pagination'
import 'swiper/scss/grid'

const modules = [Navigation, Pagination, Grid]
const swiperNextBtnId = uid()
const swiperPrevBtnId = uid()

const gratitudeStore = useGratitudesStore()
const { gratitudePosts, canLoadMorePosts } = useGratitudePosts()

const slideActive = ref(0)

start()

function start () {
  if (canLoadMorePosts.value) {
    gratitudeStore.loadMorePosts()
  }
}

function onSlideChange (swiper: typeof Swiper) {
  const postsPerSlide = 3
  const slidesCount = Math.ceil(swiper.slides.length / postsPerSlide)

  slideActive.value = swiper.activeIndex

  if (slideActive.value >= slidesCount - 2 && canLoadMorePosts.value) {
    gratitudeStore.loadMorePosts()
  }
}
</script>

<template>
  <div class="kt-gratitude-post-slider-wr">
    <div class="kt-gratitude-post-slider__nav">
      <kt-btn theme="tertiary"
              :data-btn-prev="swiperPrevBtnId"
              icon="chevron-left"
              round
              dense
              class="kt-gratitude-post-slider__btn"
      />

      <kt-btn theme="tertiary"
              :data-btn-next="swiperNextBtnId"
              icon="chevron-right"
              round
              dense
              class="kt-gratitude-post-slider__btn"
      />
    </div>

    <div class="kt-gratitude-post-slider__content">
      <vue-swiper :slides-per-view="1"
                  :space-between="16"
                  :modules="modules"
                  :grid="{
                    rows: 3,
                    fill: 'column'
                  }"
                  :navigation="{
                    prevEl: `[data-btn-prev='${swiperPrevBtnId}']`,
                    nextEl: `[data-btn-next='${swiperNextBtnId}']`
                  }"
                  :pagination="{
                    clickable: true,
                    dynamicBullets: true,
                    dynamicMainBullets: 3,
                    el: '.kt-gratitude-post-slider__pagination',
                  }"
                  class="kt-gratitude-post-slider"
                  @slide-change="onSlideChange"
      >
        <swiper-slide v-for="gratitude in gratitudePosts" :key="gratitude.ID">
          <kt-gratitude-post :title="gratitude.TITLE"
                             :url="gratitude.URL"
                             :datetime="gratitude.DATE_PUBLISH"
                             :icon="gratitude.ICON"
                             :icon-color="gratitude.ICON_COLOR"
                             :sender="gratitude.SENDER"
          />
        </swiper-slide>
      </vue-swiper>
    </div>

    <footer class="kt-gratitude-post-slider__footer">
      <div class="kt-gratitude-post-slider__pagination"></div>
    </footer>
  </div>
</template>

<style lang="scss">
@use 'vars';

.kt-gratitude-post-slider {
  &__nav {
    text-align: right;
  }

  &__btn {
    margin-bottom: var(--kt-gratitude-post-slider-gap);
  }

  .swiper-wrapper {
    max-height: 180px;
  }

  &__footer {
    display: flex;
    justify-content: center;
  }

  &-wr .swiper-horizontal > .swiper-pagination-bullets.swiper-pagination-bullets-dynamic,
  &-wr .swiper-pagination-horizontal.swiper-pagination-bullets.swiper-pagination-bullets-dynamic {
    --swiper-pagination-color: #525252;
    --swiper-pagination-bullet-inactive-color: #c6c6c6;
    --swiper-pagination-bullet-inactive-opacity: 1;

    margin-top: var(--kt-gratitude-post-slider-gap);
    text-align: center;
    transform: unset;
  }
}
</style>
