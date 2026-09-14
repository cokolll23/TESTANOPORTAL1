<template>
  <component :is="props.tag" class="kt-widget-layout-text" :class="layoutClasses">
    <slot>
      {{ props.text }}
    </slot>
  </component>
</template>

<script lang="ts" setup>
import { computed } from 'vue'

interface IKtWidgetLayoutTextProps {
  text?:  string;
  tag?:   'div' | 'span' | 'p';
  color?: string;
}

const props = withDefaults(defineProps<IKtWidgetLayoutTextProps>(), {
  tag: 'div',
  color: 'app-grey-5'
})

const layoutClasses = computed(() => ([
  `text-${props.color}`
]))
</script>

<style lang="scss">
.pgk {
  .kt-widget-layout-text {
    $font-size: 12px;
    $line-height: 19px;

    font-size: $font-size;
    line-height: $line-height;

    @media screen and (min-width: $breakpoint-lg) {
      $font-size: 15px;
      $line-height: 20px;

      font-size: $font-size;
      line-height: $line-height;
    }
  }
}
</style>
