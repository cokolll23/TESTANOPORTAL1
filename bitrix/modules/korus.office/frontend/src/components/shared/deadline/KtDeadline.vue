<script lang="ts" setup>
import { computed } from 'vue'
import { useDateFormat } from '@vueuse/core'
import { KtIcon } from '@/components/shared'

interface IProps {
  date: string;
  isOverdue: boolean;
}

const props = defineProps<IProps>()

const iconColor = computed(() => {
  return props.isOverdue ? 'var(--kt-ui-color-danger)' : 'var(--kt-ui-color-success)'
})
</script>

<template>
  <div class="kt-deadline">
    <kt-icon name="reminder" class="kt-deadline__icon" :style="{ 'background-color': iconColor }" />
    <span class="kt-deadline__text">{{ props.date }}</span>
  </div>
</template>

<style lang="scss">
.kt-deadline {
  display: inline-flex;
  align-items: center;
  gap: 4px;

  &__text {
    color: var(--kt-ui-text-secondary);
  }
}
</style>
