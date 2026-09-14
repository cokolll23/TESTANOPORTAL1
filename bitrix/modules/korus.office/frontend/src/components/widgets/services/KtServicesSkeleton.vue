<script lang="ts" setup>
import { ref, computed } from 'vue'
import { useElementSize } from '@vueuse/core'

import { KtSkeleton } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitleSkeleton } from '@/components/lk'
import { KtServiceSkeleton } from '@/components/widgets'

const sliderRef = ref<null | HTMLElement>(null)
const sliderSize = useElementSize(sliderRef)

const serviceCount = computed(() => {
  if (sliderSize.width.value >= 900) return 3
  if (sliderSize.width.value >= 600) return 2
  else return 1
})
</script>

<template>
  <kt-widget-wrapper class="kt-services-skeleton-wr">
    <div class="kt-services-skeleton">
      <header class="kt-services-skeleton__header">
        <kt-widget-title-skeleton/>
      </header>

      <div class="kt-services-skeleton__content">
        <div class="kt-services-skeleton__top">
          <div class="kt-services-skeleton-slider__nav">
            <kt-skeleton type="circle" size="32px" class="kt-services-skeleton-slider__btn"/>
            <kt-skeleton type="circle" size="32px" class="kt-services-skeleton-slider__btn"/>
          </div>
        </div>

        <div class="kt-services-skeleton-slider" ref="sliderRef">
          <kt-service-skeleton v-for="service in serviceCount" :key="service"/>
        </div>
      </div>
    </div>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-services-skeleton {
  &-wr {
    background-color: var(--kt-widget-profile-services-bg, var(--kt-ui-layer-middle-accent-blue));
  }

  &__content {
    margin-top: var(--kt-services-offset);
  }

  &__top {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    margin-bottom: 20px;
  }

  &-slider {
    display: flex;
    gap: var(--kt-ui-offset-lg);

    &__nav {
      display: flex;
      gap: 10px;
    }

    .kt-service-skeleton {
      flex: 1;
    }
  }
}
</style>
