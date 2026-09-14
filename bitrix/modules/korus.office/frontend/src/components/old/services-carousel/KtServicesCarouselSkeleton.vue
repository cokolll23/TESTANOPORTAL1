<template>
  <q-carousel :model-value="props.modelValue"
              class="kt-services-carousel-skeleton"
              @update:model-value="$emit('update:modelValue', $event)"
  >
    <q-carousel-slide v-for="(slide, index) in props.slides"
                      :key="index"
                      :name="index"
                      class="kt-services-carousel__slide"
    >
      <div class="row items-stretch q-col-gutter-md">
        <div v-for="widget in slide"
             :key="widget.id"
             :class="`col-${12 / props.widgetsPerSlide}`"
        >
          <slot :widget="widget"></slot>
        </div>
      </div>
    </q-carousel-slide>

    <template v-if="props.controlsVisible" #control>
      <q-carousel-control position="top-right"
                          :offset="[0, 0]"
                          class="kt-services-carousel-skeleton__control"
      >
        <q-skeleton type="QBtn" size="36px" />
        <q-skeleton type="QBtn" size="36px" />
      </q-carousel-control>
    </template>
  </q-carousel>
</template>

<script lang="ts" setup>
import { IService } from 'stores/services'

interface IKtServicesCarouselProps {
  modelValue:      number;
  slides:          IService[][];
  widgetsPerSlide: number;
  controlsVisible: boolean;
}

const props = defineProps<IKtServicesCarouselProps>()
</script>

<style lang="scss">
.pgk {
  .kt-services-carousel-skeleton {
    $width: 100%;
    $height: auto;
    $background-color: transparent;

    width: $width;
    height: $height;
    overflow: initial;
    background-color: $background-color;

    .kt-widget-layout-skeleton {
      $min-height: 275px;

      min-height: $min-height;
    }

    &__slide {
      $padding: 0;

      padding: $padding;
    }

    &__control {
      $top: unset;
      $bottom: -45px;

      display: flex;
      align-items: center;
      top: $top;
      bottom: $bottom;

      @media screen and (min-width: $breakpoint-xs) {
        $top: -63px;
        $bottom: unset;

        top: $top;
        bottom: $bottom;
      }
    }

    &__control .q-skeleton {
      @media screen and (max-width: $breakpoint-sm) {
        display: none;
      }
    }

    &__control .q-skeleton + .q-skeleton {
      $margin-left: 10px;

      margin-left: $margin-left;
    }
  }
}
</style>
