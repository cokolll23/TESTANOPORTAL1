<template>
  <q-select :model-value="props.modelValue"
            :options="props.options"
            :disable="props.disable"
            :multiple="props.multiple"
            option-value="VALUE"
            option-label="NAME"
            outlined
            dropdown-icon="kt:dropdown"
            class="kt-select"
            @update:model-value="$emit('update:modelValue', $event)"
  />
</template>

<script lang="ts" setup>
import { ISelectOption } from '@/models'

interface IKtSelectProps {
  modelValue: any;
  options:    ISelectOption[];
  disable?:   boolean;
  multiple?:  boolean;
}

const props = withDefaults(defineProps<IKtSelectProps>(), {
  disable: false,
  multiple: false
})
defineEmits(['update:modelValue'])
</script>

<style lang="scss">
.pgk {
  .kt-select {
    $font-size: 14px;
    $line-height: 18px;

    font-size: $font-size;
    line-height: $line-height;

    @media screen and (min-width: $breakpoint-lg) {
      $font-size: 14px;
      $line-height: 18px;

      font-size: $font-size;
      line-height: $line-height;
    }

    .q-field__marginal {
      $font-size: 10px;
      $color: var(--q-app-grey-8);

      font-size: $font-size;
      color: $color;
    }
  }
}
</style>
