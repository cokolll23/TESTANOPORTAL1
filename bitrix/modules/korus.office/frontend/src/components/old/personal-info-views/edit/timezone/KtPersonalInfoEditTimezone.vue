<template>
  <kt-widget-layout-row :label="props.label" class="timezone">
    <div class="row items-center q-col-gutter-md">
      <div class="col-sm-6 col-xs-12">
        <kt-select :model-value="props.timezoneAuto"
                   :options="props.timezoneItemsAuto"
                   @update:model-value="$emit('update:timezoneAuto', $event)"
        />
      </div>

      <div v-if="showTimezoneList" class="col-6">
        <kt-select :model-value="props.timezone"
                   :options="props.timezoneItems"
                   @update:model-value="$emit('update:timezone', $event)"
        />
      </div>
    </div>
  </kt-widget-layout-row>
</template>

<script lang="ts" setup>
import { computed, watch } from 'vue'
import { KtWidgetLayoutRow } from 'components/old/widget-layout-row'
import { KtSelect } from 'components/old/select'
import { ISelectOption } from '@/models'

interface IKtPersonalInfoEditTimezoneProps {
  label:             string;

  timezone:          ISelectOption;
  timezoneAuto:      ISelectOption;

  timezoneItems:     ISelectOption[];
  timezoneItemsAuto: ISelectOption[];
}

const props = defineProps<IKtPersonalInfoEditTimezoneProps>()
const emit  = defineEmits(['update:timezoneAuto', 'update:timezone'])

const showTimezoneList = computed(() => props.timezoneAuto?.VALUE === 'N')

watch(showTimezoneList, () => {
  if (!showTimezoneList.value) {
    emit('update:timezone', null)
  }
})
</script>
