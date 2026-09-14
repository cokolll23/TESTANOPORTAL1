<template>
  <kt-widget-layout-row :label="props.label">
    <kt-checkbox :model-value="props.modelValue"
                 @update:model-value="$emit('update:modelValue', $event)"
    />
  </kt-widget-layout-row>
</template>

<script lang="ts" setup>
import { KtWidgetLayoutRow } from 'components/old/widget-layout-row'
import { KtCheckbox } from 'components/old/checkbox'

interface IProps {
  modelValue: boolean;
}

const props = defineProps<IProps>()
defineEmits(['update:modelValue'])
</script>
