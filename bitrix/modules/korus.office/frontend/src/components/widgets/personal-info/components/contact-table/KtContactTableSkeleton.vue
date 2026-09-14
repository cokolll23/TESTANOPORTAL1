<script lang="ts" setup>
import { KtSkeleton } from '@/components/shared'
</script>

<template>
  <table class="kt-contact-table-skeleton">
    <tbody>
      <tr v-for="row in 7" :key="row">
        <td class="kt-contact-table-skeleton__label">
          <kt-skeleton type="text" width="130px" height="16px" />
        </td>

        <td class="kt-contact-table-skeleton__value">
          <kt-skeleton type="text" width="200px" height="16px" />
        </td>
      </tr>
    </tbody>
  </table>
</template>

<style lang="scss">
.kt-contact-table-skeleton {
  border-collapse: collapse;

  tr {
    display: flex;
    flex-wrap: wrap;

    @media screen and (min-width: 768px) {
      display: revert;
    }
  }

  td {
    width: 100%;
    padding-right: var(--kt-ui-offset-lg);

    @media screen and (min-width: 768px) {
      width: revert;
    }
  }

  tr:not(:last-child) &__label {
    padding-bottom: var(--kt-ui-offset-md);

    @media screen and (min-width: 768px) {
      padding-bottom: var(--kt-ui-offset-lg);
    }
  }

  tr:not(:last-child) &__value {
    padding-bottom: var(--kt-ui-offset-lg);
  }

  &__label,
  &__value {
    vertical-align: top;
  }

  &__label {
    width: var(--kt-contact-table-title-width);
  }
}
</style>
