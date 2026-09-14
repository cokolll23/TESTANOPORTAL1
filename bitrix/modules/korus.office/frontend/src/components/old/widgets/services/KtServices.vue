<template>
  <kt-widget-layout :title="$t('services.title')" theme="light" class="kt-services">
    <template #title-suffix>
      <a href="/lk/service" class="kt-services__all-link">
        <q-icon name="kt:arrow" class="on-left" />
        {{ $t('services.allServicesLink') }}
      </a>
    </template>

    <kt-tabs v-model="activeTab" :tabs="tabs" theme="light" />

    <q-tab-panels v-model="activeTab"
                  animated
                  transition-prev="jump-right"
                  transition-next="jump-left"
                  class="kt-tab-panels"
    >
      <q-tab-panel name="main" class="kt-tab-panel">
        <kt-services-carousel v-model="slideActiveMain"
                              :slides="slides.main"
                              :widgets-per-slide="widgetsPerSlide"
                              :controls-visible="slidesControlsVisible"
        >
          <template v-slot="{ widget }">
            <kt-service :title="widget.TITLE"
                        :details="widget.DETAILS"
                        :image="widget.IMAGE"
                        :color="widget.COLOR"
                        :buttons="widget.BUTTONS"
                        :title-suffix="widget.TITLE_SUFFIX"
                        class="full-height"
            />
          </template>
        </kt-services-carousel>
      </q-tab-panel>

      <q-tab-panel name="favorites" class="kt-tab-panel">
        <kt-services-carousel v-if="slides.favorites.length"
                              v-model="slideActiveFavorites"
                              :slides="slides.favorites"
                              :widgets-per-slide="widgetsPerSlide"
                              :controls-visible="slidesControlsVisible"
        >
          <template v-slot="{ widget }">
            <kt-favorite-service :title="widget.TITLE"
                                 :details="widget.DETAILS"
                                 :image="widget.IMAGE"
                                 :color="widget.COLOR"
                                 :buttons="widget.BUTTONS"
                                 class="full-height"
            />
          </template>
        </kt-services-carousel>
        <div class="column items-center" v-else>
          <div class="q-mb-md">Не добавлено ни одного сервиса в избранное</div>
          <kt-button
            href="/lk/service"
            theme="primary"
            unelevated
            rounded
          >{{ $t('services.allServicesLink') }}</kt-button>
        </div>
      </q-tab-panel>
    </q-tab-panels>
  </kt-widget-layout>
</template>

<script lang="ts" setup>
import { onMounted } from 'vue'
import { useTabs } from '@/composables/services/useTabs'
import { useSlides } from '@/composables/services/useSlides'

import { KtWidgetLayout } from 'components/old/widget-layout'
import { KtTabs } from 'components/old/tabs'
import { KtServicesCarousel } from 'components/old/services-carousel'
import { KtService, KtFavoriteService } from 'components/old/widgets'

import servicesBgImage from 'assets/services-bg.png'
import KtButton from 'components/old/button/KtButton.vue'

const { tabs, activeTab } = useTabs()
const {
  slides,
  slideActiveMain,
  slideActiveFavorites,
  widgetsPerSlide,
  slidesControlsVisible
} = useSlides(activeTab)
const servicesBg = `url('${servicesBgImage}')`

onMounted(() => {
  if (!slides.main.length) {
    activeTab.value = 'favorites'
  }
})
</script>

<style lang="scss">
.pgk {
  .kt-services {
    $color: var(--q-app-white);
    $background-image:
      v-bind(servicesBg),
      linear-gradient(100deg, rgba(0, 0, 0, 0.28) -0.39%, rgba(0, 0, 0, 0) 65%);
    $background-repeat: no-repeat;
    $background-size: cover;

    color: $color;
    background-image: $background-image;
    background-repeat: $background-repeat;
    background-size: $background-size;

    &__all-link {
      $color: rgba($app-white, 0.7);
      $color-hover: $app-white;

      display: inline-flex;
      align-items: center;
      color: $color;

      &:hover,
      &:focus,
      &:focus-visible {
        color: $color-hover;
      }

      .q-icon {
        $size: 20px;
        $font-size: 14px;
        $border: 1px solid var(--q-white);
        $border-radius: 4px;

        width: $size;
        height: $size;
        font-size: $font-size;
        border: $border;
        border-radius: $border-radius;

        @media screen and (max-width: $breakpoint-xs) {
          $size: 15px;

          width: $size;
          height: $size;
        }
      }
    }

    & > .q-separator {
      background-color: rgba($app-white, 0.5);
    }
  }
  @media screen and (max-width: $breakpoint-xs) {
    .on-left {
      margin-right: 8px;
    }
  }
}
</style>
