<script lang="ts" setup>
import { defineSlots, type VNode } from 'vue'
import type { IEditField } from '@/stores/personal-fields'

import vueDraggable from 'vuedraggable'
import { KtBtnLink } from '@/components/shared'

interface IProps {
  modelValue:   IEditField[];
  sortDisabled: boolean;
  filter?:     string;
}

interface ISlots {
  value: (scope: {
    row:   IEditField;
    index: number;
  }) => VNode;
}

interface IEmits {
  (e: 'update:model-value', event: IEditField[]): void;
}

const props = withDefaults(defineProps<IProps>(), {
  sortDisabled: false
})
const slots = defineSlots<ISlots>()
const emit = defineEmits<IEmits>()

function getRowClassName (field: IEditField) {
  return `kt-contact-edit-field--${field.name.toLowerCase()}`
}

function isDragEnabled (field: IEditField) {
  if (!props.filter) {
    return true
  }

  return !props.filter.includes(getRowClassName(field))
}

function updateModel (event: IEditField[]) {
  emit('update:model-value', event)
}
</script>

<template>
  <table class="kt-contact-edit-table">
    <vue-draggable :model-value="props.modelValue"
                   :filter="props.filter"
                   :disabled="props.sortDisabled"
                   :animation="160"
                   item-key="name"
                   tag="tbody"
                   group="contact-fields"
                   handle=".kt-contact-edit-table__dragging-btn"
                   @update:model-value="updateModel"
    >
      <template #item="{ element, index }">
        <tr :class="getRowClassName(element)">
          <td v-if="!props.sortDisabled" class="kt-contact-edit-table__dragging">
            <kt-btn-link v-if="isDragEnabled(element)"
                         theme="secondary"
                         icon="draggable"
                         class="kt-contact-edit-table__dragging-btn"
            />
          </td>

          <td class="kt-contact-edit-table__label">{{ element.title }}</td>

          <td class="kt-contact-edit-table__value">
            <slot name="value" :row="element" :index="index"></slot>
          </td>
        </tr>
      </template>
    </vue-draggable>
  </table>
</template>

<style lang="scss">
@use 'vars';

.kt-contact-edit-table {
  border-collapse: collapse;

  tr {
    display: flex;
    flex-wrap: wrap;

    @media screen and (min-width: 768px) {
      display: revert;
    }
  }

  td:not(:last-child) {
    padding-right: var(--kt-ui-offset-lg);
  }

  tr:not(:last-child) td {
    @media screen and (min-width: 768px) {
      padding-bottom: var(--kt-ui-offset-lg);
    }
  }

  &__dragging,
  &__label {
    padding-bottom: var(--kt-ui-offset-md);
  }

  &__dragging {
    width: 24px;
    color: var(--kt-ui-gray-40);
  }

  &__label {
    width: var(--kt-contact-edit-table-title-width);
    flex-grow: 1;
    color: var(--kt-contact-edit-table-title-color);

    @media screen and (min-width: 768px) {
      flex-grow: revert;
    }
  }

  &__value {
    width: 100%;
    padding-bottom: var(--kt-ui-offset-lg);

    @media screen and (min-width: 768px) {
      width: revert;
    }
  }
}
</style>
