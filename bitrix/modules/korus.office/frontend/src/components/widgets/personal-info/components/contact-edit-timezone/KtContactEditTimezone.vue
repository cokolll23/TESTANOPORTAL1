<script lang="ts" setup>
import { computed, watch } from 'vue'
import type { ISelectOption } from '@/models'

import { KtSelect } from '@/components/shared'

interface IProps {
  timezone:          ISelectOption;
  timezoneAuto:      ISelectOption;
  timezoneItems:     ISelectOption[];
  timezoneItemsAuto: ISelectOption[];
}

interface IEmits {
  (e: 'update:timezone', event: null | ISelectOption): void;
  (e: 'update:timezone-auto', events: ISelectOption): void;
}

const props = defineProps<IProps>()
const emit = defineEmits<IEmits>()

const showTimezoneList = computed(() => props.timezoneAuto?.VALUE === 'N')

watch(showTimezoneList, () => {
  if (!showTimezoneList.value) {
    emit('update:timezone', null)
  }
})
</script>

<template>
  <div class="kt-contact-edit-timezone">
    <kt-select :model-value="props.timezoneAuto"
               :options="props.timezoneItemsAuto"
               @update:model-value="$emit('update:timezoneAuto', $event)"
    />

    <kt-select v-if="showTimezoneList"
               :model-value="props.timezone"
               :options="props.timezoneItems"
               @update:model-value="$emit('update:timezone', $event)"
    />
  </div>
</template>

<style lang="scss">
.kt-contact-edit-timezone {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 16px;
}
</style>
