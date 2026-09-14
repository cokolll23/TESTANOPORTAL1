<template>
  <kt-widget-layout-row :label="props.label">
    <kt-datepicker :model-value="props.modelValue"
                   @update:model-value="$emit('update:modelValue', $event)"
    />
  </kt-widget-layout-row>
</template>

<script lang="ts" setup>
import { KtWidgetLayoutRow } from 'components/old/widget-layout-row'
import { KtDatepicker } from 'components/old/datepicker'

interface IKtPersonalInfoEditDateProps {
  label:      string;
  modelValue: string;
}

const props = defineProps<IKtPersonalInfoEditDateProps>()
defineEmits(['update:modelValue'])
</script>
