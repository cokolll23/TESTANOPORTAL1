<template>
  <kt-widget-layout-skeleton title=""
                             theme="light"
                             class="kt-services-skeleton"
  >
    <kt-tabs-skeleton theme="light" />

    <q-tab-panels v-model="activeTab" class="kt-tab-panels-skeleton">
      <q-tab-panel name="main" class="kt-tab-panel-skeleton">
        <kt-services-carousel-skeleton v-model="slideActiveMain"
                                       :slides="slides"
                                       :widgets-per-slide="widgetsPerSlide"
                                       controls-visible
        >
          <template v-slot="{ widget }">
            <kt-service-skeleton v-bind="widget" class="full-height" />
          </template>
        </kt-services-carousel-skeleton>
      </q-tab-panel>
    </q-tab-panels>
  </kt-widget-layout-skeleton>
</template>

<script lang="ts" setup>
import { useTabs } from '@/composables/services/useTabs'
import { useSlides } from '@/composables/services/useSlides'

import { KtWidgetLayoutSkeleton } from 'components/old/widget-layout'
import { KtTabsSkeleton } from 'components/old/tabs'
import { KtServicesCarouselSkeleton } from 'components/old/services-carousel'
import { KtServiceSkeleton } from 'components/old/widgets/service'

import servicesBgImage from 'assets/services-bg.png'

const { activeTab } = useTabs()
const { slideActiveMain, widgetsPerSlide } = useSlides(activeTab)
const slides = [
  [
    { id: 1 },
    { id: 2 },
    { id: 3 }
  ]
]

const servicesBg = `url('${servicesBgImage}')`
</script>

<style lang="scss">
.pgk {
  .kt-services-skeleton {
    $color: var(--q-app-white);
    $background-image:
      v-bind(servicesBg),
      linear-gradient(100deg, rgba(0, 0, 0, 0.28) -0.39%, rgba(0, 0, 0, 0) 65%);
    $background-repeat: no-repeat;

    color: $color;
    background-image: $background-image;
    background-repeat: $background-repeat;
  }
}
</style>
