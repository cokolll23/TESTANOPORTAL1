<template>
  <kt-widget-layout-skeleton :title="$t('requests.title')"
                             class="kt-requests-skeleton"
  >
    <kt-tabs-skeleton v-model="activeTab" :count="3" />

    <q-table :rows="rows"
             :columns="columns"
             row-key="name"
             wrap-cells
             separator="horizontal"
             :no-data-label="$t('requests.table.noData')"
    >
      <template #header="props">
        <q-tr :props="props">
          <q-th v-for="col in props.cols"
                :key="col.name"
                :props="props"
          >
            <q-skeleton type="text" :style="col.style" />
          </q-th>
        </q-tr>
      </template>

      <template #body="props">
        <q-tr :props="props">
          <q-td key="title" :props="props">
            <q-skeleton type="rect" width="100%" height="30px" />
          </q-td>

          <q-td key="dateCreate" :props="props">
            <q-skeleton type="text" width="100%" />
          </q-td>

          <q-td key="datePlan" :props="props">
            <q-skeleton type="text" width="100%" />
          </q-td>

          <q-td key="status" :props="props">
            <q-skeleton type="text" width="100%" />
          </q-td>

          <q-td key="executor" :props="props">
            <kt-user-card-skeleton size="xs" />
          </q-td>

          <q-td key="comment" :props="props">
            <q-skeleton type="rect" size="20px" />
          </q-td>
        </q-tr>
      </template>

      <template #bottom>
        <kt-pagination-skeleton />

        <div class="kt-requests-per-page">
          <div class="kt-requests-per-page__label">
            <q-skeleton type="text" width="70px" height="14px" />
          </div>

          <q-skeleton type="rect" width="40px" height="30px" />
        </div>
      </template>
    </q-table>
  </kt-widget-layout-skeleton>
</template>

<script lang="ts" setup>
import { useTabs } from '@/composables/requests/useTabs'
import { KtWidgetLayoutSkeleton } from 'components/old/widget-layout'
import { KtTabsSkeleton } from 'components/old/tabs'
import { KtUserCardSkeleton } from 'components/old/user-card'
import { KtPaginationSkeleton } from 'components/old/pagination'

const { activeTab } = useTabs()

const columns = [
  { name: 'title',      label: '', field: 'title',      style: 'min-width: 300px' },
  { name: 'dateCreate', label: '', field: 'dateCreate', style: 'min-width: 120px' },
  { name: 'datePlan',   label: '', field: 'datePlan',   style: 'min-width: 120px' },
  { name: 'status',     label: '', field: 'status',     style: 'min-width: 120px' },
  { name: 'executor',   label: '', field: 'executor',   style: 'min-width: 200px' },
  { name: 'comment',    label: '', field: 'comment',    style: 'min-width: 60px'  }
]

const rows = [{}, {}, {}, {}, {}]
</script>

<style lang="scss">
.pgk {
  .kt-requests-skeleton {
    .q-table {
      $width: 100%;

      width: $width;

      &__bottom {
        $min-height: 30px;
        $padding-top: 20px;

        min-height: $min-height;
        padding-top: $padding-top;
      }
    }

    &-per-page {
      $margin-left: auto;

      display: flex;
      flex-wrap: wrap;
      align-items: center;
      margin-left: $margin-left;

      &__label {
        $margin-right: 16px;

        margin-right: $margin-right;
      }
    }

    .q-field--auto-height .q-field__control,
    .q-field--auto-height .q-field__native {
      $min-height: 28px;

      min-height: $min-height;
    }

    .q-field--outlined .q-field__control::before {
      $border-color: var(--q-app-grey-13);

      border-color: $border-color;
    }

    .q-field--auto-height .q-field__control {
      $padding: 0 5px 0 9px;

      padding: $padding;
    }

    .q-field__marginal {
      $height: 28px;
      $padding-left: 10px;
      $transform: translateY(1px);

      height: $height;
      padding-left: $padding-left;
      transform: $transform;
    }
  }
}
</style>
