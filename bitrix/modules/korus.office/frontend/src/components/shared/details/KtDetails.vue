<script lang="ts" setup>
import { computed, defineModel } from 'vue'
import { KtExpansionItem, KtBtnLink, KtIcon } from '@/components/shared'

interface IProps {
  expandText:   string;
  collapseText: string;
}

const props = withDefaults(defineProps<IProps>(), {
  expandText: 'Развернуть',
  collapseText: 'Свернуть'
})

const isExpanded = defineModel<boolean>({ required: true })

const expandStateText = computed(() => {
  return isExpanded.value ? props.collapseText : props.expandText
})

function toggle () {
  isExpanded.value = !isExpanded.value
}
</script>

<template>
  <div class="kt-details">
    <kt-expansion-item v-model="isExpanded" hide-expand-icon expand-icon-toggle>
      <template #header>
        <div class="kt-details__header">
          <slot name="header"></slot>

          <kt-btn-link v-if="$slots.default"
                       :label="expandStateText"
                       theme="primary"
                       underline
                       class="kt-details__expand-btn"
                       @click="toggle"
          >
            <template #iconRight>
              <kt-icon name="arrow-drop-down"
                       class="kt-details__expand-btn-icon"
                       :class="{ 'rotate-180': isExpanded }"
              />
            </template>
          </kt-btn-link>
        </div>
      </template>

      <div class="kt-details__content">
        <slot></slot>
      </div>
    </kt-expansion-item>
  </div>
</template>

<style lang="scss">
@use 'vars';

.kt-details {
  &__header {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }

  &__expand-btn {
    margin-top: var(--kt-details-offset);
  }

  &__expand-btn-icon {
    font-size: var(--kt-details-expand-btn-icon-size);
    transform-origin: 50% 50%;
    transition: transform 160ms linear;
  }

  .q-expansion-item__container .q-item {
    padding: 0;
  }

  &__content {
    margin-top: var(--kt-details-offset);
  }
}
</style>
