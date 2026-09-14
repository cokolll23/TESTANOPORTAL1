<template>
  <q-checkbox :model-value="props.modelValue"
              @update:model-value="$emit('update:modelValue', $event)"
              class="kt-checkbox"
              :class="layoutClass"
  />
</template>

<script lang="ts" setup>
import { computed } from 'vue'

interface IProps {
  modelValue: boolean;
  theme?:     'light' | 'dark';
}

const props = withDefaults(defineProps<IProps>(), {
  theme: 'dark'
})
defineEmits(['update:modelValue'])

const layoutClass = computed(() => ([
  `kt-checkbox--theme-${props.theme}`
]))
</script>

<style lang="scss">
.pgk {
  .kt-checkbox {
    &--theme-light {
      .q-checkbox__inner,
      .q-checkbox__inner--truthy,
      .q-checkbox__inner--indet {
        $color: var(--q-white);

        color: $color;
      }

      .q-checkbox__svg {
        $color: var(--q-primary);

        color: $color;
      }
    }

    &--theme-dark {
      .q-checkbox__inner,
      .q-checkbox__inner--truthy,
      .q-checkbox__inner--indet {
        $color: var(--q-primary);

        color: $color;
      }

      .q-checkbox__svg {
        $color: var(--q-primary);

        color: $color;
      }
    }
  }
}
</style>
