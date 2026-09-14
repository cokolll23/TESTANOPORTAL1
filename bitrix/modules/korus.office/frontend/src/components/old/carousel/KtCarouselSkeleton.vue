<template>
  <q-carousel :model-value="props.modelValue"
              class="kt-carousel-skeleton"
              @update:model-value="$emit('update:modelValue', $event)"
  >
    <q-carousel-slide v-for="(slide, index) in props.slides"
                      :key="index"
                      :name="index"
                      class="kt-carousel__slide"
    >
      <div ref="slideInnerRef">
        <slot :slide="slide"></slot>
      </div>
    </q-carousel-slide>

    <template v-if="props.controlsVisible" #control>
      <q-carousel-control position="bottom-left"
                          :offset="[0, 0]"
                          class="kt-carousel-skeleton__control"
      >
        <q-skeleton type="QBtn" size="10px" />
        <q-skeleton type="QBtn" size="10px" />
      </q-carousel-control>
    </template>
  </q-carousel>
</template>

<script lang="ts" setup>
interface IProps {
  modelValue:      number;
  slides:          unknown[];
  controlsVisible: boolean;
}

const props = defineProps<IProps>()
</script>

<style lang="scss">
.pgk {
  .kt-carousel-skeleton {
    $width: 100%;
    $height: auto;
    $padding-bottom: 25px;
    $background-color: transparent;

    width: $width;
    height: $height;
    padding-bottom: $padding-bottom;
    overflow: hidden;
    background-color: $background-color;

    &__slide {
      $padding: 0;

      padding: $padding;
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
