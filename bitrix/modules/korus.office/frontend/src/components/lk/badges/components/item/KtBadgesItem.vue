<script lang="ts" setup>
import { computed } from 'vue'
import { KtImg } from '@/components/shared'

interface IProps {
  image: string;
  color: string;
}

const props = defineProps<IProps>()
const layoutStyles = computed(() => `background-color: ${props.color}`)
</script>

<template>
  <span class="kt-badges-item" :style="layoutStyles">
    <kt-img :src="props.image" initial-ratio="1" ratio="1" />
  </span>
</template>

<style lang="scss">
@use 'vars';

.kt-badges-item {
  width: var(--kt-badges-item-size);
  height: var(--kt-badges-item-size);
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  border: 2px solid var(--kt-badges-item-border-color);
  border-radius: 50%;
  cursor: pointer;

  .q-img {
    width: 50%;
    height: 50%;
  }
}
</style>
