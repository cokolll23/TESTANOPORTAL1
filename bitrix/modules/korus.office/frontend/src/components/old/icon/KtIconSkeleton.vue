<template>
  <div class="kt-icon-skeleton-wrapper" :class="layoutClass">
    <q-skeleton type="circle" />
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'

interface IKtIconProps {
  size?: 'md' | 'sm';
}

const props = withDefaults(defineProps<IKtIconProps>(), {
  size: 'md'
})
const layoutClass = computed(() => ([
  `kt-icon-skeleton-wrapper--${props.size}`
]))
</script>

<style lang="scss" scoped>
.pgk {
  .kt-icon-skeleton-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 50%;

    &--md .q-skeleton {
      $width: 42px;
      $height: 42px;

      width: $width;
      height: $height;
    }

    &--sm .q-skeleton {
      $width: 36px;
      $height: 36px;

      width: $width;
      height: $height;
    }
  }
}
</style>
