<template>
  <q-carousel :model-value="props.modelValue"
              swipeable
              animated
              transition-prev="slide-right"
              transition-next="slide-left"
              infinite
              ref="carousel"
              class="kt-services-carousel"
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
      <q-carousel-control position="top-right" :offset="[0, 0]" class="kt-services-carousel__control">
        <q-btn round
               outline
               size="16px"
               color="transparent"
               text-color="white"
               icon="kt:arrow"
               class="kt-services-carousel__button-prev rotate-180"
               @click="$refs.carousel.previous()"
        />

        <q-btn round
               outline
               size="16px"
               color="transparent"
               text-color="white"
               icon="kt:arrow"
               class="kt-services-carousel__button-next"
               @click="$refs.carousel.next()"
        />
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
defineEmits(['update:modelValue'])
</script>

<style lang="scss">
.pgk {
  .kt-services-carousel {
    $width: 100%;
    $height: auto;
    $background-color: transparent;

    width: $width;
    height: $height;
    overflow: initial;
    background-color: $background-color;

    .kt-widget-layout {
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

      top: $top;
      bottom: $bottom;

      @media screen and (min-width: $breakpoint-xs) {
        $top: -63px;
        $bottom: unset;

        top: $top;
        bottom: $bottom;
      }
    }

    &__button-prev,
    &__button-next {
      $width: 36px;
      $height: 36px;

      min-width: $width;
      min-height: $height;

      &::before {
        $color: rgba($app-grey-15, 0.5);

        color: $color;
      }

      .q-icon > svg {
        $width: 16px;
        $height: 16px;

        width: $width;
        height: $height;
      }
    }

    &__button-next {
      $margin-left: 10px;

      margin-left: $margin-left;
    }
  }
}
</style>
