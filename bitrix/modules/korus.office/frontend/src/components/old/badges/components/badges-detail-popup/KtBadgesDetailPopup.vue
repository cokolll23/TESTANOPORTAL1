<template>
  <q-popup-proxy v-model="isVisible"
                 anchor="top right"
                 self="center left"
                 :offset="[15, 0]"
                 max-width="545px"
                 :breakpoint="600"
                 class="kt-popup kt-popup--proxy kt-badges-detail-popup"
  >
    <div class="kt-popup-outer">
      <div class="kt-popup-inner">
        <div class="kt-popup__close-btn-wrapper">
          <kt-button theme="text"
                     round
                     size="6px"
                     icon="kt:close"
                     color="app-grey-6"
                     class="kt-popup__close-btn"
                     @click="closePopup"
          />
        </div>

        <h2 class="kt-popup__title">{{ $t('badges.popup.title') }}</h2>

        <kt-carousel v-model="slideActive"
                     :slides="slides"
                     :controls-visible="slidesControlsVisible"
                     class="q-mt-sm"
        >
          <template v-slot="{ slide: slideBadges }">
            <kt-badges-detail v-for="badge in slideBadges" :key="badge.name" :badge="badge" />
          </template>
        </kt-carousel>
      </div>
    </div>
  </q-popup-proxy>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useBadgesStore } from 'stores/badges'
import { useSlides } from 'composables/useSlides'

import { KtButton } from 'components/old/button'
import { KtCarousel } from 'components/old/carousel'
import { KtWidgetLayoutText } from 'components/old/widget-layout-text'
import { KtBadgesDetail } from '../badges-detail'

const { badges } = storeToRefs(useBadgesStore())

const badgesPerSlide = ref(4)
const { slideActive, slides, slidesControlsVisible } = useSlides(badges, badgesPerSlide)

const isVisible = ref(false)
const closePopup = () => {
  isVisible.value = false
}
</script>

<style lang="scss">
.pgk {
  .kt-badges-detail-popup {
    .kt-carousel {
      min-height: fit-content;
    }

    .kt-badges-detail + .kt-badges-detail {
      $margin-top: 10px;

      margin-top: $margin-top;
    }
  }
}
</style>
