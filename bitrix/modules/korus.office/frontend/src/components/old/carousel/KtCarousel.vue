<script lang="ts" setup>
import { KtBtn } from '@/components/shared'

interface IProps {
  modelValue:      number;
  slides:          unknown[];
  controlsVisible: boolean;
}

const props = defineProps<IProps>()
defineEmits(['update:modelValue'])
</script>

<template>
  <q-carousel :model-value="props.modelValue"
              swipeable
              animated
              transition-prev="slide-right"
              transition-next="slide-left"
              infinite
              ref="carousel"
              class="kt-carousel"
              @update:model-value="$emit('update:modelValue', $event)"
  >
    <q-carousel-slide v-for="(slide, index) in props.slides"
                      :key="index"
                      :name="index"
                      class="kt-carousel__slide"
    >
      <div class="kt-carousel__slide-inner">
        <slot :slide="slide"></slot>
      </div>
    </q-carousel-slide>

    <template v-if="props.controlsVisible" #control>
      <q-carousel-control position="bottom-left" :offset="[0, 0]" class="kt-carousel__control">
        <kt-btn theme="secondary"
                round
                dense
                class="kt-carousel__button-prev"
                @click="$refs.carousel.previous()"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"><path fill="currentColor" d="m6.25 10 6.25-6.25.875.875L8 10l5.375 5.375-.875.875L6.25 10Z"/></svg>
        </kt-btn>

        <kt-btn theme="secondary"
                round
                dense
                class="kt-carousel__button-next"
                @click="$refs.carousel.next()"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"><path fill="currentColor" d="M13.75 10 7.5 16.25l-.875-.875L12 10 6.625 4.625 7.5 3.75 13.75 10Z"/></svg>
        </kt-btn>
      </q-carousel-control>
    </template>
  </q-carousel>
</template>

<style lang="scss">
.pgk {
  .kt-carousel {
    width: 100%;
    height: auto;
    padding-bottom: 25px;
    overflow: hidden;
    background-color: transparent;

    &__slide {
      padding: 0;

      &-inner {
        box-sizing: content-box;
      }
    }

    &__control {
      width: 100%;
    }

    &__button-prev,
    &__button-next {
      flex-shrink: 0;
    }

    &__button-prev .q-icon,
    &__button-next .q-icon {
      font-size: 12px;
    }

    &__button-next {
      margin-left: 4px;
    }
  }
}
</style>
