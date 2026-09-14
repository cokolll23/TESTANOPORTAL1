<script lang="ts" setup>
import { defineSlots, type VNode } from 'vue'
import type { IPersonalField } from '@/stores/personal-fields'

interface IProps {
  rows: IPersonalField[];
}

interface ISlots {
  value: (scope: {
    row:   IPersonalField;
    index: number;
  }) => VNode;
}

const props = defineProps<IProps>()
const slots = defineSlots<ISlots>()
</script>

<template>
  <table class="kt-contact-table">
    <tbody>
      <tr v-for="(row, index) in props.rows" :key="row.name">
        <td class="kt-contact-table__label">{{ row.title }}</td>
        <td class="kt-contact-table__value">
          <slot name="value" :row="row" :index="index"></slot>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<style lang="scss">
@use 'vars';

.kt-contact-table {
  border-collapse: collapse;

  tr {
    display: flex;
    flex-wrap: wrap;

    @media screen and (min-width: 768px) {
      display: revert;
    }
  }

  td {
    vertical-align: top;
    padding-right: var(--kt-ui-offset-lg);
  }

  &__label {
    width: 100%;
    color: var(--kt-contact-table-title-color);

    @media screen and (min-width: 768px) {
      width: var(--kt-contact-table-title-width);
    }
  }

  tr:not(:last-child) &__label {
    padding-bottom: var(--kt-ui-offset-md);

    @media screen and (min-width: 768px) {
      padding-bottom: var(--kt-ui-offset-lg);
    }
  }

  &__value {
    width: 100%;

    @media screen and (min-width: 768px) {
      width: revert;
    }
  }

  tr:not(:last-child) &__value {
    padding-bottom: var(--kt-ui-offset-lg);
  }
}
</style>
