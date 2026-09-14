<template>
  <q-btn round
         :outline="!props.isActive"
         :color="color"
         :icon="props.icon"
         :style="layoutStyles"
         class="kt-gratitude"
         @click="$emit('click', $event)"
  >
    <q-badge v-if="props.isActive"
             rounded
             floating
             color="white"
             text-color="app-grey-7"
             class="kt-gratitude__badge"
    >
      {{ props.count }}
    </q-badge>
  </q-btn>
</template>

<script lang="ts" setup>
import { computed } from 'vue'

interface IProps {
  count:    number;
  icon:     string;
  color:    string;
  isActive: boolean;
}

const props = defineProps<IProps>()
defineEmits(['click'])

const color = computed(() => {
  if (props.color.startsWith('#')) {
    return undefined
  }

  return props.color
})
const layoutStyles = computed(() => {
  const styles: Record<string, string> = {}

  if (props.color.startsWith('#')) {
    styles.color = '#ffffff'
    styles['background-color'] = props.color
  }

  return styles
})
</script>

<style lang="scss">
.pgk {
  .kt-gratitude {
    $size: 36px;
    $min-width: initial;
    $min-height: initial;

    width: $size;
    height: $size;
    min-width: $min-width;
    min-height: $min-height;

    .q-icon {
      $font-size: 20px;

      font-size: $font-size;
    }

    &__badge {
      $top: unset;
      $bottom: -5px;
      $right: 0;
      $box-shadow: 0 1px 2px rgba(0, 0, 0, 0.16);

      top: $top;
      bottom: $bottom;
      right: $right;
      box-shadow: $box-shadow;
    }
  }
}
</style>
