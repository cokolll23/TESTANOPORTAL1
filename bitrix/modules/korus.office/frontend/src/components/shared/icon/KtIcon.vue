<script lang="ts" setup>
import { computed } from 'vue'
import { getIconSource } from './getSource'

interface IProps {
  name:   string;
  size?:  string;
  fit?:   'auto' | 'cover' | 'contain';
  color?: string;
}

const props = withDefaults(defineProps<IProps>(), {
  fit: 'auto'
})

const layoutStyles = computed(() => ({
  'font-size': props.size,
  'mask-image': `${getIconSource(props.name)}`,
  'mask-size': props.fit,
  'background-color': props.color
}))
</script>

<template>
  <span class="kt-icon" role="presentation" :style="layoutStyles" />
</template>

<style lang="scss">
.kt-icon {
  display: inline-flex;
  width: 1em;
  height: 1em;
  line-height: 1;
  flex-shrink: 0;
  font-size: 20px;
  letter-spacing: normal;
  text-transform: none;
  white-space: nowrap;
  word-wrap: normal;
  direction: ltr;
  text-align: center;
  position: relative;
  mask-repeat: no-repeat;
  mask-position: 50% 50%;
  box-sizing: content-box;
  background-color: currentColor;
  user-select: none;
  cursor: inherit;
}
</style>
