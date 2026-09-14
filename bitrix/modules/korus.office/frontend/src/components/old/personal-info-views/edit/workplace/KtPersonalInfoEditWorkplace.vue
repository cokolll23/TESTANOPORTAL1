<template>
  <kt-widget-layout-row :label="props.label">
    <i class="text-app-grey-5">Информация редактируется только администратором портала.</i>
  </kt-widget-layout-row>
</template>

<script lang="ts" setup>
import { KtWidgetLayoutRow } from 'components/old/widget-layout-row'
import { IInputType } from '@/models'

interface IKtPersonalInfoEditTextProps {
  label: string;
  type: IInputType;
  modelValue: string;
  rules?: ((...args: unknown[]) => unknown | string)[];
  lazyRules?: boolean;
  errorMessage?: string;
}

const props = withDefaults(defineProps<IKtPersonalInfoEditTextProps>(), {
  type: 'text'
})
defineEmits(['update:modelValue'])
</script>
