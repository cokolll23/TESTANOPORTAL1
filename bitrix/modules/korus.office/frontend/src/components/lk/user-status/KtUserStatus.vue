<script lang="ts" setup>
import { reactive, computed } from 'vue'

interface IProps {
  text:     string;
  isOnline: boolean;
}

const props = defineProps<IProps>()
const status = reactive({
  color: computed(() => props.isOnline ? 'app-green-2' : 'app-deep-orange-1')
})
</script>

<template>
  <div class="kt-user-status">
    <span class="kt-user-status__circle" :class="`bg-${status.color}`"></span>
    <span class="kt-user-status__text">{{ props.text }}</span>
  </div>
</template>

<style lang="scss">
@use 'vars';

.kt-user-status {
  display: inline-flex;
  align-items: center;

  &__circle {
    width: var(--kt-user-status-circle-size);
    height: var(--kt-user-status-circle-size);
    border-radius: 50%;
  }

  &__text {
    margin-left: var(--kt-ui-offset-md);
    color: var(--kt-user-status-color);
  }
}
</style>
