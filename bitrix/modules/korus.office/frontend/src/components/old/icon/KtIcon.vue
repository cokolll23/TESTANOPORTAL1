<template>
  <div class="kt-icon-wrapper" :class="layoutClass" :style="layoutStyles">
    <q-icon :name="props.name" :color="props.color" :size="props.iconSize" />
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'

interface IKtIconProps {
  name:            string;
  color:           string;
  backgroundColor: string;
  size?:           'md' | 'sm' | 'lg';
  iconSize?:       string;
}

const props = withDefaults(defineProps<IKtIconProps>(), {
  size: 'md',
  iconSize: '24px'
})

const layoutClass = computed(() => ([
  `kt-icon-wrapper--${props.size}`,
  !props.backgroundColor.startsWith('#') ? `bg-${props.backgroundColor}` : ''
]))
const layoutStyles = computed(() => ([
  props.backgroundColor.startsWith('#') ? props.backgroundColor : ''
]))
</script>

<style lang="scss">
.pgk {
  .kt-icon-wrapper {
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background-color: v-bind('backgroundColor');

    &--md {
      $width: 32px;
      $height: 32px;

      width: $width;
      height: $height;

      @media screen and (min-width: $breakpoint-lg) {
        $width: 42px;
        $height: 42px;

        width: $width;
        height: $height;
      }
    }

    &--sm {
      $width: 36px;
      $height: 36px;

      width: $width;
      height: $height;
    }

    &--lg {
      $width: 42px;
      $height: 42px;

      width: $width;
      height: $height;
    }
  }
}
</style>
