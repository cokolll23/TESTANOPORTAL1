<script lang="ts" setup>
import { KtSkeleton, KtUserCardSkeleton, KtPaginationSkeleton } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitleSkeleton } from '@/components/lk'

const columns = [
  { name: 'id',         label: '', field: 'id',         style: 'min-width: 50px' },
  { name: 'title',      label: '', field: 'title',      style: 'min-width: 200px' },
  { name: 'dateCreate', label: '', field: 'dateCreate', style: 'min-width: 100px' },
  { name: 'datePlan',   label: '', field: 'datePlan',   style: 'min-width: 100px' },
  { name: 'status',     label: '', field: 'status',     style: 'min-width: 100px' },
  { name: 'executor',   label: '', field: 'executor',   style: 'min-width: 150px' },
  { name: 'comment',    label: '', field: 'comment',    style: 'min-width: 60px'  }
]

const rows = [{}, {}, {}, {}, {}]
</script>

<template>
  <kt-widget-wrapper>
    <div class="kt-requests-skeleton">
      <header class="kt-requests-skeleton__header">
        <kt-widget-title-skeleton />
        <kt-skeleton type="QBtn" />
      </header>

      <div class="kt-requests-skeleton__content">
        <q-table :rows="rows"
                 :columns="columns"
                 row-key="name"
                 wrap-cells
                 flat
                 hide-bottom
                 separator="horizontal"
        >
          <template #header="props">
            <q-tr :props="props">
              <q-th v-for="col in props.cols"
                    :key="col.name"
                    :props="props"
              >
                <kt-skeleton type="text" :style="col.style" />
              </q-th>
            </q-tr>
          </template>

          <template #body="props">
            <q-tr :props="props">
              <q-td key="id" :props="props">
                <kt-skeleton type="rect" width="100%" height="16px" />
              </q-td>

              <q-td key="title" :props="props">
                <kt-skeleton type="rect" width="100%" height="16px" />
              </q-td>

              <q-td key="dateCreate" :props="props">
                <kt-skeleton type="text" width="100%" height="16px" />
              </q-td>

              <q-td key="datePlan" :props="props">
                <kt-skeleton type="text" width="100%" height="16px" />
              </q-td>

              <q-td key="status" :props="props">
                <kt-skeleton type="text" width="100%" height="16px" />
              </q-td>

              <q-td key="executor" :props="props">
                <kt-user-card-skeleton show-avatar show-name size="xs" />
              </q-td>

              <q-td key="comment" :props="props">
                <kt-skeleton type="rect" size="20px" />
              </q-td>
            </q-tr>
          </template>
        </q-table>

        <kt-pagination-skeleton />
      </div>
    </div>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-requests-skeleton {
  &__header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: var(--kt-ui-offset-md);
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }
}
</style>
