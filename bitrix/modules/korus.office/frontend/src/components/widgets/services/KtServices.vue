<script lang="ts" setup>
import { ref, computed, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useI18n } from 'vue-i18n'
import { useQuasar, uid } from 'quasar'
import { Swiper } from 'swiper'
import { Navigation, Pagination, Grid } from 'swiper/modules'
import { useServicesStore, IService } from '@/stores/services'

import { KtTabs, KtTab, KtTabPanels, KtTabPanel, KtBtn } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitle, KtWidgetStub } from '@/components/lk'
import { KtService, KtFavoriteService } from '@/components/widgets'
import { Swiper as VueSwiper, SwiperSlide } from 'swiper/vue'

import favoriteServiceStub from '@/assets/widgets/favorite-service/stub.png'

import 'swiper/scss'
import 'swiper/scss/navigation'
import 'swiper/scss/pagination'
import 'swiper/scss/grid'

const $q = useQuasar()
const { t } = useI18n()
const { mainServices, favoriteServices } = storeToRefs(useServicesStore())

const tabs = [
  { name: 'main', label: t('services.tabs.main') },
  { name: 'favorites', label: t('services.tabs.favorites') }
]
const activeTab = ref('main')

const modules = [Navigation, Pagination, Grid]
const swiperNextBtnId = uid()
const swiperPrevBtnId = uid()

const hasActiveSlides = computed(() => {
  if (activeTab.value === 'main') {
    return mainServices.value.length > 0
  }

  return favoriteServices.value.length > 0
})

const swiperProps = ref<typeof Swiper['params']>({
  slidesPerView: 1,
  spaceBetween: 16,
  allowTouchMove: false,
  modules,
  navigation: {
    prevEl: `[data-btn-prev='${swiperPrevBtnId}']`,
    nextEl: `[data-btn-next='${swiperNextBtnId}']`
  },
  pagination: {
    clickable: true,
    dynamicBullets: true,
    dynamicMainBullets: 3,
    el: '.kt-services-slider__pagination'
  },
  breakpointsBase: 'container',
  breakpoints: {
    0: { slidesPerView: 1 },
    600: { slidesPerView: 2 },
    900: { slidesPerView: 3 }
  },
  class: 'kt-services-slider'
})

onMounted(() => {
  if (!mainServices.value.length) {
    activeTab.value = 'favorites'
  }
})
</script>

<template>
  <kt-widget-wrapper class="kt-services-wr">
    <article class="kt-services">
      <header class="kt-services__header">
        <kt-widget-title href="/lk/service" :text="$t('services.title')"/>
      </header>

      <div class="kt-services__content">
        <div class="kt-services__top">
          <kt-tabs v-model="activeTab" mobile-arrows shrink align="left" no-caps>
            <kt-tab v-for="tab in tabs" :key="tab.name" :name="tab.name" :label="tab.label"/>
          </kt-tabs>

          <div v-if="hasActiveSlides" class="kt-services-slider__nav">
            <kt-btn theme="tertiary"
                    :data-btn-prev="swiperPrevBtnId"
                    icon="chevron-left"
                    round
                    dense
                    class="kt-services-slider__btn"
            />

            <kt-btn theme="tertiary"
                    :data-btn-next="swiperNextBtnId"
                    icon="chevron-right"
                    round
                    dense
                    class="kt-services-slider__btn"
            />
          </div>
        </div>

        <kt-tab-panels v-model="activeTab"
                       animated
                       transition-prev="jump-right"
                       transition-next="jump-left"
        >
          <kt-tab-panel name="main">
            <vue-swiper v-if="mainServices.length > 0" v-bind="swiperProps">
              <swiper-slide v-for="service in mainServices" :key="service.TITLE">
                <kt-service :title="service.TITLE"
                            :details="service.DETAILS"
                            :image="service.IMAGE"
                            :color="service.COLOR"
                            :buttons="service.BUTTONS"
                            :title-suffix="service.TITLE_SUFFIX"
                />
              </swiper-slide>
            </vue-swiper>

            <div v-else class="kt-services__stub-wr">
              <kt-widget-stub :icon="favoriteServiceStub"
                              :text="$t('services.stub.mainDescription')"
              />
            </div>
          </kt-tab-panel>

          <kt-tab-panel name="favorites">
            <vue-swiper v-if="favoriteServices.length > 0" v-bind="swiperProps">
              <swiper-slide v-for="service in favoriteServices" :key="service.TITLE">
                <kt-favorite-service :title="service.TITLE"
                                     :details="service.DETAILS"
                                     :image="service.IMAGE"
                                     :color="service.COLOR"
                                     :buttons="service.BUTTONS"
                />
              </swiper-slide>
            </vue-swiper>

            <div v-else class="kt-services__stub-wr">
              <kt-widget-stub :icon="favoriteServiceStub"
                              :text="$t('services.stub.favoritesDescription')"
              />
              <kt-btn theme="primary" :label="$t('services.allServicesLink')" href="/lk/service"/>
            </div>
          </kt-tab-panel>
        </kt-tab-panels>
      </div>

      <footer v-if="hasActiveSlides" class="kt-services__footer">
        <div class="kt-services-slider__pagination"></div>
      </footer>
    </article>
  </kt-widget-wrapper>
</template>

<style lang="scss">
@use 'vars';

.kt-services {
  &-wr {
    background-color: var(--kt-widget-profile-services-bg, var(--kt-ui-layer-middle-accent-blue));
  }

  &__content {
    margin-top: var(--kt-services-offset);
  }

  &__top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
  }

  &-slider {
    .swiper-slide {
      height: revert;
    }

    .kt-service,
    .kt-favorite-service {
      height: 100%;
    }
  }

  &__footer {
    display: flex;
    justify-content: flex-end;
  }

  &-wr .swiper-horizontal > .swiper-pagination-bullets.swiper-pagination-bullets-dynamic,
  &-wr .swiper-pagination-horizontal.swiper-pagination-bullets.swiper-pagination-bullets-dynamic {
    --swiper-pagination-color: #525252;
    --swiper-pagination-bullet-inactive-color: #c6c6c6;
    --swiper-pagination-bullet-inactive-opacity: 1;

    margin-top: var(--kt-services-offset);
    text-align: center;
    transform: unset;
  }

  &__stub-wr {
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding: var(--kt-ui-offset-lg);
    border-radius: var(--kt-ui-border-radius-lg);
    background-color: var(--kt-ui-layer-01);

    .kt-btn {
      align-self: center;
    }
  }
}
</style>
