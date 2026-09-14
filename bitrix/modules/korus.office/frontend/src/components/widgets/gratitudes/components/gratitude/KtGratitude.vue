<script lang="ts" setup>
import { computed } from 'vue'
import { KtBadge, KtTooltip } from '@/components/shared'

interface IProps {
  icon:        string;
  color:       string;
  isActive:    boolean;
  isClickable: boolean;
  showCount:   boolean;
  count?:      number;
  showName?:   boolean;
  name?:       string;
}

const props = withDefaults(defineProps<IProps>(), {
  isClickable: false,
  showCount: false,
  showName: false
})

const layoutClasses = computed(() => ({
  'is-active': props.isActive,
  'is-clickable': props.isClickable
}))
const layoutStyles = computed(() => ({
  borderColor: props.isActive ? props.color : 'var(--kt-gratitude-border-color)',
  backgroundColor: props.isActive ? props.color : 'var(--kt-gratitude-bg)'
}))
const imageStyles = computed(() => ({
  maskImage: `url(${props.icon})`,
  backgroundColor: props.isActive ? 'var(--kt-ui-white)' : 'var(--kt-ui-icon-disabled)'
}))
</script>

<template>
  <button type="button" class="kt-gratitude" :class="layoutClasses">
    <span class="kt-gratitude__icon" :style="imageStyles"></span>

    <kt-tooltip v-if="props.showName" anchor="bottom middle" self="top middle" :offset="[0, 4]">
      {{ props.name }}
    </kt-tooltip>

    <kt-badge v-if="props.showCount && props.isActive" floating outline>{{ props.count }}</kt-badge>
  </button>
</template>

<style lang="scss">
@use 'vars';

.kt-gratitude {
  width: var(--kt-gratitude-size);
  height: var(--kt-gratitude-size);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  position: relative;
  padding: 0;
  border-radius: 50%;
  border-width: 2px;
  border-style: solid;
  border-color: v-bind('layoutStyles.borderColor');
  background-color: v-bind('layoutStyles.backgroundColor');
  transition: background-color 160ms linear, border-color 160ms linear;

  &.is-clickable {
    cursor: pointer;
  }

  &.is-clickable:not(.is-active):hover {
    box-shadow: var(--kt-ui-box-shadow-base);
    background-color: var(--kt-gratitude-bg-hover);
  }

  &.is-clickable.is-active:hover {
    border-color: var(--kt-gratitude-border-color-hover);
  }

  &__icon {
    width: 50%;
    height: 50%;
    mask-repeat: no-repeat;
    mask-size: contain;
    mask-position: 50% 50%;
  }
}
</style>
