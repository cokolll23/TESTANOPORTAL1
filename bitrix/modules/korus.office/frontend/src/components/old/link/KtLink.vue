<script lang="ts" setup>
import { computed } from 'vue'
import { ILinkTheme, ILinkTarget } from '@/models'

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
      <q-icon :name="props.icon" :size="props.iconSize" />
    </span>

    <span class="kt-link__text">
      <slot>{{ props.text }}</slot>
    </span>
  </a>
</template>

<style lang="scss">
.pgk {
  .kt-link {
    text-decoration: none;
    cursor: pointer;

    &--primary-theme {
      color: var(--ui-link-primary-color);

      &:hover,
      &:focus-visible {
        color: var(--ui-link-secondary-color);
      }
    }

    &--secondary-theme {
      color: var(--ui-link-secondary-color);

      &:hover,
      &:focus-visible {
        color: var(--ui-link-primary-color);
      }
    }

    &--empty {
      cursor: default;
    }
  }
}
</style>
