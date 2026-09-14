<script lang="ts" setup>
import { computed } from 'vue'
import { ILinkTheme, ILinkTarget } from '@/models'
import { KtIcon } from '@/components/shared'

interface IProps {
  href?:     string;
  text?:     string;
  icon?:     string;
  iconSize?: string;
  theme?:    ILinkTheme
  target?:   ILinkTarget
}

const props = withDefaults(defineProps<IProps>(), {
  theme: 'primary',
  target: '_self'
})

const layoutClasses = computed(() => ([
  `kt-link--${props.theme}-theme`,
  !props.href || props.href === '#' ? 'kt-link--empty' : null
]))
</script>

<template>
  <a :href="props.href"
     :target="props.target"
     :class="layoutClasses"
     class="kt-link"
  >
    <span v-if="props.icon" class="kt-link__icon">
      <kt-icon :name="props.icon" :size="props.iconSize" />
    </span>

    <span class="kt-link__text">
      <slot>{{ props.text }}</slot>
    </span>
  </a>
</template>

<style lang="scss">
@use 'design';

/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-link {
    text-decoration: none;
    cursor: pointer;
    color: var(--ui-link-color);

    &:hover {
      color: var(--ui-link-color-hover);
    }

    &:focus-visible {
      color: var(--ui-link-color-focus);
    }

    &:active {
      color: var(--ui-link-color-active);
    }

    &:disabled {
      color: var(--ui-link-color-disabled);
    }

    &--empty {
      cursor: default;
    }
  }
}
</style>
