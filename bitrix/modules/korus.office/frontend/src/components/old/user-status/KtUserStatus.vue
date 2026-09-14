<template>
  <div class="kt-user-status">
    <span class="kt-user-status__circle" :class="`bg-${status.color}`"></span>
    <span class="kt-user-status__text">{{ props.text }}</span>
  </div>
</template>

<script lang="ts" setup>
import { reactive, computed } from 'vue'

interface IKtUserStatusProps {
  text:     string;
  isOnline: boolean;
}

const props = defineProps<IKtUserStatusProps>()
const status = reactive({
  color: computed(() => props.isOnline ? 'app-green-2' : 'app-deep-orange-1')
})
</script>

<style lang="scss">
.pgk {
  .kt-user-status {
    display: inline-flex;
    align-items: center;

    &__circle {
      $width: 10px;
      $height: 10px;

      width: $width;
      height: $height;
      border-radius: 50%;
    }

    &__text {
      $margin-left: 5px;
      $font-size: 10px;
      $line-height: 14px;
      $color: var(--q-app-grey-8);

      margin-left: $margin-left;
      font-size: $font-size;
      line-height: $line-height;
      color: $color;
    }
  }
}
</style>
