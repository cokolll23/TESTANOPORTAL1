<template>
  <kt-widget-layout :title="$t('requests.title')" class="kt-requests">
    <kt-tabs v-model="activeTab" :tabs="tabs"/>

    <q-scroll-area visible
                   :thumb-style="thumbStyles"
                   :horizontal-thumb-style="horizontalThumbStyles"
                   class="kt-requests__scroll js-requests-table-scroll"
    >
      <q-table :rows="list"
               :columns="columns"
               :pagination="{
                 rowsPerPage: computed(() => nav.pageSize)
               }"
               row-key="name"
               wrap-cells
               separator="horizontal"
               class="js-requests-table"
               :no-data-label="$t('requests.table.noData')"
      >
        <template #header="props">
          <q-tr :props="props">
            <q-th v-for="col in props.cols"
                  :key="col.name"
                  :props="props"
                  class="text-uppercase"
            >
              {{ col.label }}
            </q-th>
          </q-tr>
        </template>

        <template #body="props">
          <q-tr :props="props">
            <q-td key="id" :props="props">
              {{ props.row.id }}
            </q-td>
            <q-td key="title" :props="props">
              <kt-link  @click="showDetails(props.row.id)" theme="secondary">
                <kt-text-clamp :text="props.row.title" :max-lines="2" show-full-in-tooltip/>
              </kt-link>
            </q-td>

            <q-td key="dateCreate" :props="props">
              {{ props.row.dateCreate }}
            </q-td>

            <q-td key="dateEnd" :props="props">
              {{ props.row.dateEnd }}
            </q-td>

            <q-td key="maxDeadline" :props="props">
              {{ props.row.maxDeadline }}
            </q-td>

            <q-td key="status" :props="props">
              <span :style="{ 'color': getStatusColor(props.row.status.color) }">
                {{ props.row.status.text }}
              </span>
            </q-td>

            <q-td key="response" :props="props">
              <template v-if="props.row.response.length>0">
                <template v-if="props.row.response.length===1">
                  <kt-user-card v-for="(item, index) in props.row.response"
                                :key="index"
                                :user="item" size="xs"/>
                </template>
                <template v-else>
                  <q-btn :label="t('requests.table.manyUser')" color="primary">
                    <q-menu>
                      <q-list v-for="(item, index) in props.row.response" :key="index"
                              dense style="min-width: 100px">
                        <q-item clickable v-close-popup>
                          <q-item-section>
                            <kt-user-card
                              :user="item" size="xs"/>
                          </q-item-section>
                        </q-item>
                      </q-list>

                    </q-menu>
                  </q-btn>
                </template>
              </template>
            </q-td>

            <q-td key="comment" :props="props">
              <template v-if="props.row.comment.text">
                <span class="kt-requests__table-comment">
                  <q-icon name="kt:chat-dots" :color="!props.row.comment.new ? 'primary' : 'secondary'"/>
                  <q-tooltip anchor="bottom middle" self="top middle" @show="markCommentViewed(props.row)">
                    {{ props.row.comment.text }}
                  </q-tooltip>
                </span>
              </template>
            </q-td>
          </q-tr>
        </template>

        <template #bottom></template>
      </q-table>
    </q-scroll-area>

    <div class="full-width flex items-center q-mt-md">
      <kt-pagination v-if="hasPagination"
                     v-model="nav.currentPage"
                     :maxPage="totalPages"
                     :is-prev-disabled="isFirstPage"
                     :is-next-disabled="isLastPage"
                     :prev-btn-handler="prevPage"
                     :next-btn-handler="nextPage"
      />

      <div class="kt-requests-per-page">
        <div class="kt-requests-per-page__label">
          {{ $t('pagination.perPage') }}:
        </div>

        <kt-select v-model="nav.pageSize" :options="perPageOptions"/>
      </div>
    </div>
  </kt-widget-layout>
</template>

<script lang="ts" setup>
import { computed, nextTick, onMounted } from 'vue'
import { storeToRefs } from 'pinia'

import { useI18n } from 'vue-i18n'
import { IRequestRow, useRequestsStore } from 'stores/requests'
import { useTabs } from '@/composables/requests/useTabs'
import { usePagination } from '@/composables/usePagination'
import { KtLink } from 'components/old/link'

import { KtWidgetLayout } from 'components/old/widget-layout'
import { KtTabs } from 'components/old/tabs'
import { KtSelect } from 'components/old/select'
import { KtUserCard } from 'components/old/user-card'
import { KtPagination } from 'components/old/pagination'
import { KtTextClamp } from 'components/old/text-clamp'

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

const columns = [
  { name: 'id', label: t('requests.table.id'), align: 'left', field: 'title', style: 'min-width: 50px' },
  { name: 'title', label: t('requests.table.title'), align: 'left', field: 'title', style: 'min-width: 300px' },
  {
    name: 'dateCreate',
    label: t('requests.table.dateCreate'),
    align: 'left',
    field: 'dateCreate',
    style: 'min-width: 120px'
  },
  {
    name: 'dateEnd',
    label: t('requests.table.dateEnd'),
    align: 'left',
    field: 'dateEnd',
    style: 'min-width: 120px'
  },
  {
    name: 'maxDeadline',
    label: t('requests.table.deadline'),
    align: 'left',
    field: 'maxDeadline',
    style: 'min-width: 120px'
  },
  { name: 'status', label: t('requests.table.status'), align: 'left', field: 'status', style: 'min-width: 120px' },
  {
    name: 'response',
    label: t('requests.table.executor'),
    align: 'left',
    field: 'response',
    style: 'min-width: 200px'
  },
  { name: 'comment', label: t('requests.table.comment'), align: 'left', field: 'comment', style: 'min-width: 60px' }
]
const TABLE_SELECTOR = '.js-requests-table'
const TABLE_SCROLL_SELECTOR = '.js-requests-table-scroll'
const TABLE_SCROLL_OFFSET = 10
const BX = window.BX

const thumbStyles = {
  borderRadius: '4px'
}
const horizontalThumbStyles = {
  height: '4px'
}

const getStatusColor = (theme: string) => {
  const mapColor = {
    purple: '#9A77F7',
    blue: '#4589FF',
    green: '#24A148',
    red: '#FA4D56',
    yellow: '#FAC000',
    cyan: '#00A09B'
  }

  return mapColor[theme as keyof typeof mapColor]
}

const setTableHeight = () => {
  const tableEl: HTMLDivElement | null = document.querySelector(TABLE_SELECTOR)
  const tableElScroll: HTMLDivElement | null = document.querySelector(TABLE_SCROLL_SELECTOR)

  if (tableEl === null || tableElScroll === null) {
    return false
  }

  tableElScroll.style.height = tableEl.offsetHeight + TABLE_SCROLL_OFFSET + 'px'
}
const showDetails = (id: number) => {
  BX.SidePanel.Instance.open('/lk/request/detail.php?id=' + id, {
    width: 1000,
    allowChangeHistory: false,
    cacheable:false,
    mobileFriendly: true,
    events: {
      onLoad: function (event) {
        document.body.style.overflow = 'hidden'
      },
      onClose: function (event) {
        document.body.style.overflow = 'inherit'
      }
    }
  })
}
const markCommentViewed = (row: IRequestRow) => {
  if (row.comment.new) {
    requestsStore.markCommentViewed(row.id)
  }
}

onMounted(setTableHeight)
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

<style lang="scss">
.pgk {
  .kt-requests {
    &__scroll {
      $width: 100%;

      width: $width;
    }

    .q-table {
      $width: 100%;
      $font-size: 12px;
      $line-height: 19px;

      width: $width;
      font-size: $font-size;
      line-height: $line-height;

      @media screen and (min-width: $breakpoint-lg) {
        $font-size: 15px;
        $line-height: 20px;

        font-size: $font-size;
        line-height: $line-height;
      }

      th,
      td {
        $padding: 4px 16px;

        padding: $padding;
      }

      th {
        $font-family: 'OpenSans', sans-serif;
        $font-weight: 600;
        $font-size: 9px;
        $line-height: 12px;
        $color: var(--q-app-grey-5);

        font-family: $font-family;
        font-weight: $font-weight;
        font-size: $font-size;
        line-height: $line-height;
        color: $color;

        @media screen and (min-width: $breakpoint-lg) {
          $font-size: 12px;
          $line-height: 16px;

          font-size: $font-size;
          line-height: $line-height;
        }
      }

      tbody td {
        font-size: inherit;
      }

      &__bottom:not(.q-table__bottom--nodata) {
        $min-height: 0;
        $height: 0;
        $padding: 0;

        min-height: $min-height;
        height: $height;
        padding: $padding;
      }

      &__bottom.q-table__bottom--nodata {
        $min-height: 30px;
        $height: 30px;

        min-height: $min-height;
        height: $height;
      }
    }

    &__table-comment {
      $font-size: 20px;

      font-size: $font-size;
      cursor: pointer;
    }

    &-per-page {
      $margin-left: auto;

      display: flex;
      flex-wrap: wrap;
      align-items: center;
      margin-left: $margin-left;

      @media screen and (max-width: $breakpoint-xs) {
        $margin-top: 16px;

        margin: 0 auto;
        margin-top: $margin-top;
      }

      &__label {
        $margin-right: 16px;
        $font-family: 'OpenSans Semibold', sans-serif;
        $font-size: 12px;
        $line-height: 16px;
        $color: var(--q-app-grey-3);

        margin-right: $margin-right;
        font-family: $font-family;
        color: $color;

        @media screen and (min-width: $breakpoint-lg) {
          $font-size: 14px;
          $line-height: 19px;

          font-size: $font-size;
          line-height: $line-height;
        }
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

    .q-field__native,
    .q-field__prefix,
    .q-field__suffix,
    .q-field__input {
      $color: var(--q-app-grey-5);

      color: $color;
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
