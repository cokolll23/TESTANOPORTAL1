<script lang="ts" setup>
import { computed } from 'vue'
import { KtIcon } from '@/components/shared'

interface IProps {
  statusId: number;
  text:     string;
}

const props = defineProps<IProps>()
const statusMap = new Map([
  [1,   'NEW'],
  [2,   'IN_PROCESS'],
  [3,   'DONE'],
  [4,   'REJECTED'],
  [5,   'REVOKED'],
  [100, 'COORDINATION'],
  [101, 'AGREED']
])
const iconName = computed(() => {
  switch (statusMap.get(props.statusId)) {
    case 'DONE':
      return 'check-filled'
    case 'REJECTED':
    case 'REVOKED':
      return 'warning-filled'
    default:
      return 'clock-filled'
  }
})
const iconBgColor = computed(() => {
  switch (statusMap.get(props.statusId)) {
    case 'DONE':
      return 'var(--kt-ui-color-success)'
    case 'REJECTED':
    case 'REVOKED':
      return 'var(--kt-ui-color-danger)'
    default:
      return 'var(--kt-ui-gray-50)'
  }
})
</script>

<template>
  <div class="kt-process-indicator">
    <kt-icon :name="iconName" :style="{ 'background-color': iconBgColor }" class="kt-process-indicator__icon" />
    <span>{{ props.text }}</span>
  </div>
</template>

<style lang="scss">
.kt-process-indicator {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 12px;
  line-height: 1.33;

  &__icon {
    flex-shrink: 0;
    font-size: 20px;
  }
}
</style>
