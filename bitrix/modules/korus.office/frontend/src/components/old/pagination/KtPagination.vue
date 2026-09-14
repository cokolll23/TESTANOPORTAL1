<template>
  <div class="kt-pagination">
    <q-btn unelevated
           outline
           :disable="props.isPrevDisabled"
           padding="0"
           class="kt-pagination__prev-btn"
           ref="kt-pagination-prev-btn"
           @click="props.prevBtnHandler"
    >
      <q-icon name="kt:dropdown" size="12px" />
    </q-btn>

    <q-pagination :max="props.maxPage"
                  color=""
                  active-color="primary"
                  unelevated
                  size="12px"
                  padding="0"
                  :model-value="props.modelValue"
                  @update:model-value="$emit('update:modelValue', $event)"
                  :ellipses="true"
                  :max-pages="windowSize < 600 ? 1 : 7"
    />

    <q-btn unelevated
           outline
           :disable="props.isNextDisabled"
           padding="0"
           class="kt-pagination__next-btn"
           ref="kt-pagination-next-btn"
           @click="props.nextBtnHandler"
    >
      <q-icon name="kt:dropdown" size="12px" />
    </q-btn>
  </div>
</template>

<script lang="ts" setup>
import { useWindowSize } from '@vueuse/core'
const { width: windowSize } = useWindowSize()

interface IKtPaginationProps {
  modelValue:      number;
  prevBtnHandler:  () => void;
  nextBtnHandler:  () => void;
  maxPage:         number;
  isPrevDisabled?: boolean;
  isNextDisabled?: boolean;
}

const props = withDefaults(defineProps<IKtPaginationProps>(), {
  isPrevDisabled: false,
  isNextDisabled: false
})
defineEmits(['update:modelValue'])
</script>

<style lang="scss">
.pgk {
  .kt-pagination {
    display: flex;
    flex-wrap: wrap;

    @media screen and (max-width: $breakpoint-xs) {
      margin: 0 auto;
    }

    &__prev-btn,
    &__next-btn {
      $size: 24px;
      $border-color: var(--q-app-grey-13);
      $color: var(--ui-link-color) !important;

      min-height: $size;
      width: $size;
      border-color: $border-color;
      color: $color;
    }

    &__prev-btn {
      .q-icon {
        $transform: rotate(90deg);

        transform: $transform;
      }
    }

    &__next-btn {
      $margin-left: 10px;
      margin-left: $margin-left;

      @media screen and (max-width: $breakpoint-xs) {
        $margin-left: 6px;
        margin-left: $margin-left;
      }

      .q-icon {
        $transform: rotate(-90deg);

        transform: $transform;
      }
    }

    .q-pagination .q-btn {
      $size: 24px;
      $padding: 0;
      $color: var(--ui-link-color) !important;
      $background-color: var(--q-app-grey-18);
      $margin-left: 10px;

      width: $size;
      height: $size;
      padding: $padding;
      color: $color;
      background-color: $background-color;
      margin-left: $margin-left;

      @media screen and (max-width: $breakpoint-xs) {
        $margin-left: 6px;
        margin-left: $margin-left;
      }

      &.bg-primary {
        color: var(--ui-btn-color) !important;
      }
    }
  }
}
</style>
