<script lang="ts" setup>
import { computed } from 'vue'
import { KtSkeleton } from '@/components/shared'

interface IProps {
  showAvatar?: boolean;
  showName?:   boolean;
  size?:       'sm' | 'md' | 'lg';
}

const props = withDefaults(defineProps<IProps>(), {
  showAvatar: false,
  showName: false,
  size: 'md'
})

const isDefaultSize = computed(() => ['sm', 'md', 'lg'].includes(props.size))
const layoutClasses = computed(() => ({
  [`size-${props.size}`]: isDefaultSize.value
}))
</script>

<template>
  <div class="kt-user-card-skeleton" :class="layoutClasses">
    <kt-skeleton v-if="props.showAvatar" type="circle" class="kt-user-card-skeleton__avatar-wr" />

    <div v-if="props.showName || $slots.undername" class="kt-user-card-skeleton__content">
      <kt-skeleton type="text" width="150px" height="16px" />

      <span v-if="$slots.undername" class="kt-user-card-skeleton__undername">
        <slot name="undername"></slot>
      </span>
    </div>
  </div>
</template>

<style lang="scss">
@use 'vars';

/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-user-card-skeleton {
    display: flex;
    align-items: center;
    gap: 15px;

    &.size-sm {
      --kt-user-card-photo-size: var(--kt-user-card-photo-size-sm);
      --kt-user-card-name-font-size: var(--kt-user-card-name-font-size-sm);
      --kt-user-card-name-line-height: var(--kt-user-card-name-line-height-sm);
      --kt-user-card-undername-font-size: var(--kt-user-card-undername-font-size-sm);
      --kt-user-card-undername-line-height: var(--kt-user-card-undername-line-height-sm);

      gap: 10px;
    }

    &.size-md {
      --kt-user-card-photo-size: var(--kt-user-card-photo-size-md);
      --kt-user-card-name-font-size: var(--kt-user-card-name-font-size-md);
      --kt-user-card-name-line-height: var(--kt-user-card-name-line-height-md);
      --kt-user-card-undername-font-size: var(--kt-user-card-undername-font-size-md);
      --kt-user-card-undername-line-height: var(--kt-user-card-undername-line-height-md);
    }

    &.size-lg {
      --kt-user-card-photo-size: var(--kt-user-card-photo-size-lg);
      --kt-user-card-name-font-size: var(--kt-user-card-name-font-size-lg);
      --kt-user-card-name-line-height: var(--kt-user-card-name-line-height-lg);
      --kt-user-card-undername-font-size: var(--kt-user-card-undername-font-size-lg);
      --kt-user-card-undername-line-height: var(--kt-user-card-undername-line-height-lg);

      gap: 20px;
    }

    &__avatar-wr {
      width: var(--kt-user-card-photo-size);
      height: var(--kt-user-card-photo-size);
    }

    &__content {
      display: flex;
      flex-direction: column;
      overflow: hidden;
      gap: 4px;
    }
  }
}
</style>
