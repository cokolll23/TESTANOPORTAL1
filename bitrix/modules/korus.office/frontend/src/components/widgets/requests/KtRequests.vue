<script lang="ts" setup>
import { computed, nextTick, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useI18n } from 'vue-i18n'
import { usePersonalStore } from '@/stores/personal'
import { useRequestsStore, type IRequestRow } from '@/stores/requests'
import { useTabs } from '@/composables/requests/useTabs'
import { usePagination } from '@/composables/usePagination'

import {
  KtTabs,
  KtTab,
  KtScrollArea,
  KtLink,
  KtTextClamp,
  KtBtn,
  KtTag,
  KtDeadline,
  KtPagination
} from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitle } from '@/components/lk'
import { KtRequestProcess } from '@/components/widgets/requests/components'

const requestsStore = useRequestsStore()
const { t } = useI18n()
const { activeTab } = useTabs()
const {
  isFirstPage,
  isLastPage,
  totalPages,
  hasPagination,
  perPageOptions,
  prevPage,
  nextPage
} = usePagination(requestsStore)
const { tabs, list, nav } = storeToRefs(requestsStore)
const { currentUser } = storeToRefs(usePersonalStore())

const columns = [
  { name: 'id', label: t('requests.table.id'), align: 'left', field: 'id', style: 'min-width: 50px' },
  { name: 'title', label: t('requests.table.title'), align: 'left', field: 'title', style: 'min-width: 280px' },
  { name: 'process', label: t('requests.table.process'), align: 'left', field: 'indicator', style: 'min-width: 200px' },
  { name: 'status', label: t('requests.table.status'), align: 'left', field: 'status', style: 'min-width: 130px' },
  {
    name: 'maxDeadline',
    label: t('requests.table.deadline'),
    align: 'left',
    field: 'maxDeadline',
    style: 'min-width: 150px'
  }
]
const TABLE_SELECTOR = '.js-requests-table'
const TABLE_SCROLL_SELECTOR = '.js-requests-table-scroll'
const TABLE_SCROLL_OFFSET = 10
const BX = window.BX

onMounted(setTableHeight)

function setTableHeight () {
  const tableEl: HTMLDivElement | null = document.querySelector(TABLE_SELECTOR)
  const tableElScroll: HTMLDivElement | null = document.querySelector(TABLE_SCROLL_SELECTOR)

  if (tableEl === null || tableElScroll === null) {
    return false
  }

  tableElScroll.style.height = tableEl.offsetHeight + TABLE_SCROLL_OFFSET + 'px'
}

function showDetails (id: number) {
  BX.SidePanel.Instance.open('/lk/request/detail.php?id=' + id, {
    width: 1000,
    allowChangeHistory: false,
    cacheable: false,
    mobileFriendly: true,
    events: {
      onLoad: function () {
        document.body.style.overflow = 'hidden'
      },
      onClose: function () {
        document.body.style.overflow = 'inherit'
      }
    }
  })
}

function markCommentViewed (row: IRequestRow) {
  if (row.comment.new) {
    requestsStore.markCommentViewed(row.id)
  }
}

/**
 * После загрузки заявок и обновления DOM перестраиваем скролл
 */
requestsStore.$onAction(({ after, name }) => {
  if (name !== 'load') {
    return false
  }

  after(async () => {
    await nextTick()
    setTableHeight()
  })
})
</script>

<template>
  <kt-widget-wrapper>
    <div class="kt-requests">
      <header class="kt-requests__header">
        <kt-widget-title :text="$t('requests.title')"/>
        <kt-btn theme="primary" href="/lk/service/#/" :label="$t('common.btn.add')" icon-right="add"/>
      </header>

      <div class="kt-requests__content">
        <div class="kt-requests__top">
          <kt-tabs v-model="activeTab" mobile-arrows shrink align="left" no-caps>
            <kt-tab v-for="tab in tabs" :key="tab.name" :name="tab.name" :label="tab.label"/>
          </kt-tabs>
        </div>

        <kt-scroll-area visible class="kt-requests__scroll js-requests-table-scroll">
          <q-table :rows="list"
                   :columns="columns"
                   :pagination="{
                     rowsPerPage: computed(() => nav.pageSize)
                   }"
                   row-key="name"
                   wrap-cells
                   separator="horizontal"
                   class="js-requests-table"
                   hide-bottom
                   flat
                   :no-data-label="$t('requests.table.noData')"
          >
            <template #header="props">
              <q-tr :props="props">
                <q-th v-for="col in props.cols" :key="col.name" :props="props">
                  {{ col.label }}
                </q-th>
              </q-tr>
            </template>

            <template #body="props">
              <q-tr :props="props">
                <q-td key="id" :props="props">
                  <kt-text-clamp :content="props.row.id" :lines="1"/>
                </q-td>

                <q-td key="title" :props="props">
                  <kt-link theme="secondary" @click="showDetails(props.row.id)" class="cursor-pointer">
                    <kt-text-clamp :content="props.row.title" :lines="2"/>
                  </kt-link>
                </q-td>

                <q-td key="process" :props="props">
                  <kt-request-process :author="currentUser"
                                      :executors="props.row.response"
                                      :status-id="props.row.status.id"
                                      :indicator-text="props.row.indicator"
                                      :message-count="props.row.countMessage"
                  />
                </q-td>

                <q-td key="status" :props="props">
                  <kt-tag :theme="props.row.status.theme" :text="props.row.status.text" inline/>
                </q-td>

                <q-td key="maxDeadline" :props="props">
                  <kt-deadline :date="props.row.maxDeadlineFormatted" :isOverdue="props.row.isOverdue"/>
                </q-td>
              </q-tr>
            </template>
          </q-table>
        </kt-scroll-area>

        <kt-pagination v-if="hasPagination"
                       v-model="nav.currentPage"
                       v-model:page-size="nav.pageSize"
                       :page-size-options="perPageOptions"
                       :has-pagination="hasPagination"
                       :maxPage="totalPages"
                       :is-prev-disabled="isFirstPage"
                       :is-next-disabled="isLastPage"
                       class="q-mt-md"
        />
      </div>
    </div>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-requests {
  &__header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: var(--kt-ui-offset-md);
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }

  &__top {
    display: flex;
  }

  &__scroll {
    width: 100%;
    margin-top: var(--kt-widget-wrapper-header-offset);
  }

  .q-table {
    width: 100%;
    color: var(--kt-ui-text-primary);

    th,
    td {
      padding: 4px 16px;
      font-size: inherit;
      font-weight: 400;
      border-color: var(--kt-ui-border-subtle-01);
    }

    th {
      color: var(--kt-ui-text-secondary);
    }

    &__bottom:not(.q-table__bottom--nodata) {
      min-height: 0;
      height: 0;
      padding: 0;
    }

    &__bottom.q-table__bottom--nodata {
      min-height: 32px;
      height: 32px;
    }
  }

  &__table-comment {
    font-size: 20px;
    cursor: pointer;
  }
}
</style>
