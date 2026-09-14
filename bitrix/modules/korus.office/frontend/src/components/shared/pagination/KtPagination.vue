<script lang="ts" setup>
import { computed } from 'vue'
import { useBreakpoints } from '@/composables/shared'
import { KtBtn, KtSelect } from '@/components/shared'

interface IProps {
  modelValue:       number;
  maxPage:          number;
  hasPagination:    boolean;
  pageSize?:        number;
  pageSizeOptions?: number[];
  isPrevDisabled?:  boolean;
  isNextDisabled?:  boolean;
}

interface IEmits {
  (e: 'update:model-value', pageNumber: number): void;
  (e: 'update:page-size', pageSize: number): void;
}

const props = withDefaults(defineProps<IProps>(), {
  isPrevDisabled: false,
  isNextDisabled: false
})
const emit = defineEmits<IEmits>()

const { breakpoints } = useBreakpoints()
const maxPages = computed(() => {
  if (breakpoints.mobile) {
    return 4
  }

  return 7
})

function updatePageNumber (pageNumber: number) {
  emit('update:model-value', pageNumber)
}

function updatePageSize (pageSize: number) {
  emit('update:page-size', pageSize)
}

function prevBtnHandler () {
  const pageNumber = props.modelValue - 1
  updatePageNumber(pageNumber)
}

function nextBtnHandler () {
  const pageNumber = props.modelValue + 1
  updatePageNumber(pageNumber)
}
</script>

<template>
  <div class="kt-pagination">
    <template v-if="props.hasPagination">
      <kt-btn theme="tertiary"
              round
              dense
              icon="chevron-left"
              :disable="props.isPrevDisabled"
              class="kt-pagination__prev-btn"
              @click="prevBtnHandler"
      />

      <q-pagination :max="props.maxPage"
                    :max-pages="maxPages"
                    boundary-numbers
                    unelevated
                    padding="0"
                    :model-value="props.modelValue"
                    @update:model-value="updatePageNumber"
      />

      <kt-btn theme="tertiary"
              round
              dense
              icon="chevron-right"
              :disable="props.isNextDisabled"
              class="kt-pagination__next-btn"
              @click="nextBtnHandler"
      />
    </template>

    <div v-if="pageSize" class="kt-pagination__page-size">
      <span>{{ $t('common.pagination.pageSize') }}:</span>
      <kt-select :model-value="pageSize"
                 :options="pageSizeOptions"
                 hide-bottom-space
                 dense
                 @update:model-value="updatePageSize"
      />
    </div>
  </div>
</template>

<style lang="scss">
@use 'vars';

/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-pagination {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 4px;

    &__prev-btn,
    &__next-btn {
      min-height: var(--kt-pagination-btn-size);
      width: var(--kt-pagination-btn-size);
      flex-shrink: 0;
      color: var(--q-app-grey-6);

      &::before {
        border-color: var(--q-app-grey-8);
      }

      .q-icon {
        width: 1em;
        font-size: 20px;
      }
    }

    .q-pagination__content,
    .q-pagination__middle {
      gap: 4px;
    }

    .q-pagination__content {
      --q-pagination-gutter-parent: 0;
      --q-pagination-gutter-child: 0;
    }

    .q-pagination .q-btn {
      width: var(--kt-pagination-btn-size);
      height: var(--kt-pagination-btn-size);
      padding: 0;
      border-radius: 50%;
      font-weight: 400;
      color: var(--kt-pagination-btn-color) !important;
      background-color: var(--kt-pagination-btn-bg) !important;

      &::before {
        box-shadow: none;
      }
    }

    .q-pagination .q-btn[aria-current='true'] {
      color: var(--kt-pagination-btn-active-color) !important;
      background-color: var(--kt-pagination-btn-active-bg) !important;
    }

    &__page-size {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: var(--kt-ui-offset-md);
      margin-left: auto;
    }
  }
}
</style>
