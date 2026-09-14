<template>
  <kt-widget-layout-row :label="props.label">
    <kt-input :model-value="props.modelValue"
              :rules="props.rules"
              :lazy-rules="props.lazyRules"
              :type="props.type"
              @update:model-value="$emit('update:modelValue', $event)"
    />
  </kt-widget-layout-row>
</template>

<script lang="ts" setup>
import { KtWidgetLayoutRow } from 'components/old/widget-layout-row'
import { KtInput } from 'components/old/input'
import { IInputType } from '@/models'

interface IKtPersonalInfoEditTextProps {
  label:         string;
  type:          IInputType;
  modelValue:    string;
  rules?:        ((...args: unknown[]) => unknown | string)[];
  lazyRules?:    boolean;
  errorMessage?: string;
}

const props = withDefaults(defineProps<IKtPersonalInfoEditTextProps>(), {
  type: 'text'
})
defineEmits(['update:modelValue'])
</script>
