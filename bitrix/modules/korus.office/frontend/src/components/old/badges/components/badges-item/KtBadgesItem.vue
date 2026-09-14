<template>
  <span class="kt-badges-item" :style="layoutStyles">
    <q-img :src="props.image" initial-ratio="1" ratio="1" />
  </span>
</template>

<script lang="ts" setup>
import { computed } from 'vue'

interface IProps {
  image: string;
  color: string;
}

const props = defineProps<IProps>()
const layoutStyles = computed(() => `background: ${props.color}`)
</script>

<style lang="scss">
.pgk {
  .kt-badges-item {
    $size: 49px;
    $border: 2px solid var(--q-white);
    $border-radius: 50%;

    width: $size;
    height: $size;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    border: $border;
    border-radius: $border-radius;
    cursor: pointer;

    .q-img {
      width: 50%;
      height: 50%;
    }
  }
}
</style>
