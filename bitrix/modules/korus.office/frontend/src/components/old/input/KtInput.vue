<template>
  <q-input :model-value="props.modelValue"
           :rules="props.rules"
           :lazy-rules="props.lazyRules"
           :type="props.type"
           :autogrow="props.autogrow"
           outlined
           class="kt-input"
           @update:model-value="onUpdateModel"
  />
</template>

<script lang="ts" setup>
import { IInputType } from '@/models'

interface IProps {
  type:          IInputType;
  modelValue:    string;
  rules?:        ((...args: unknown[]) => unknown | string)[];
  lazyRules?:    boolean;
  autogrow?:     boolean;
  errorMessage?: string;
}

const props = defineProps<IProps>()
const emit = defineEmits(['update:modelValue'])

const onUpdateModel = (event: string) => {
  emit('update:modelValue', event)
}
</script>

<style lang="scss">
.pgk {
  .kt-input {
    $font-size: 14px;
    $line-height: 18px;

    font-size: $font-size;
    line-height: $line-height;

    &:not(.q-field--error) {
      $padding-bottom: 0px;

      padding-bottom: $padding-bottom;
    }
  }
}
</style>
