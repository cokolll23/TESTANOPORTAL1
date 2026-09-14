<template>
  <kt-widget-layout-row :label="props.label">
    <kt-select :model-value="props.modelValue"
               :options="props.options"
               :multiple="props.multiple"
               @update:model-value="$emit('update:modelValue', $event)"
    />
  </kt-widget-layout-row>
</template>
<script lang="ts" setup>
import { KtWidgetLayoutRow } from 'components/old/widget-layout-row'
import { KtSelect } from 'components/old/select'
import { ISelectOption } from '@/models'

interface IKtPersonalInfoEditSelectProps {
  label:      string;
  modelValue: any;
  options:    ISelectOption[];
  multiple?:  boolean;
}

const props = withDefaults(defineProps<IKtPersonalInfoEditSelectProps>(), {
  multiple: false
})
defineEmits(['update:modelValue'])
</script>
<style lang="scss">
.pgk {
  .q-item {
    &--active {
      font-weight: bold;

      .q-item__label span:before {
        display: inline-block;
        content: '-';
        margin-right: 5px;
      }
    }
  }
}
</style>
