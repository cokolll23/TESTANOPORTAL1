<script lang="ts" setup>
import { ref, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useCompetenciesStore } from '@/stores/competencies'
import { usePermissionsStore } from '@/stores/permissions'
import { useCompetencies } from '@/composables/competencies/useCompetencies'

import { KtBtn, KtTag, KtTagSelect } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitle, KtWidgetStub } from '@/components/lk'

import stubImage from '@/assets/widgets/skills/stub.png'

const preparedTags = ref<string[]>([])

const { mode, changeMode, add, remove, cancel, search } = useCompetencies(preparedTags)
const { items, tips } = storeToRefs(useCompetenciesStore())
const { CAN_EDIT } = storeToRefs(usePermissionsStore())

const tags = computed(() => {
  return items.value.map(item => item.TITLE)
})
const searchTags = computed(() => {
  return tips.value.map(tip => tip.TITLE)
})

const isReadMode = computed(() => mode.value === 'read')
const isEditMode = computed(() => mode.value === 'edit')
const isStubMode = computed(() => mode.value === 'stub')
const isActionsVisible = computed(() => isEditMode.value || isStubMode.value)
</script>

<template>
  <kt-widget-wrapper>
    <article class="kt-competencies">
      <header class="kt-competencies__header">
        <kt-widget-title :text="$t('competencies.title')" />

        <kt-btn v-if="isReadMode && CAN_EDIT"
                theme="tertiary"
                icon="pencil"
                round
                dense
                @click="changeMode('edit')"
        />
      </header>

      <div class="kt-competencies__content">
        <div v-if="isReadMode" class="kt-competencies__tags">
          <kt-tag v-for="competence in items"
                  :key="competence.ID"
                  :text="competence.TITLE"
                  :href="`/company?apply_filter=Y&UF_COMPETENCE=${competence.ID}`"
                  outline
                  clickable
                  class="kt-competencies__tag"
          />
        </div>

        <kt-tag-select v-if="isEditMode"
                       v-model="preparedTags"
                       :tags="tags"
                       :hint-popover-tags="searchTags"
                       :hint-popover-text="$t('competencies.popup.description')"
                       class="kt-competencies__tag-select"
                       @submit="add"
                       @tag:search="search"
                       @tag:remove="remove"
        />

        <kt-widget-stub v-if="isStubMode"
                        :icon="stubImage"
                        :text="$t('competencies.stub.description')"
        />
      </div>

      <footer v-if="isActionsVisible" class="kt-competencies__footer">
        <template v-if="isStubMode">
          <kt-btn theme="primary" class="q-mx-auto" @click="changeMode('edit')">
            <span>{{ $t('common.btn.add') }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" fill="none" class="q-ml-sm"><path fill="currentColor" d="M11.125 9.375V5h-1.25v4.375H5.5v1.25h4.375V15h1.25v-4.375H15.5v-1.25h-4.375Z"/></svg>
          </kt-btn>
        </template>

        <template v-else>
          <kt-btn theme="primary" :label="$t('common.btn.save')" @click="add()" />
          <kt-btn theme="ghost" :label="$t('common.btn.cancel')" @click="cancel" />
        </template>
      </footer>
    </article>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-competencies {
  &__header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: var(--kt-ui-offset-md);
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }

  &__tags {
    display: flex;
    flex-wrap: wrap;
    gap: var(--kt-ui-offset-md);
  }

  &__tag {
    margin: 0;
  }

  &__tag-select {
    max-width: 288px;
  }

  &__footer {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: var(--kt-ui-offset-lg);
    margin-top: var(--kt-widget-wrapper-footer-offset);

    @media screen and (min-width: 360px) {
      flex-wrap: nowrap;
      justify-content: initial;
    }
  }

  &__footer .kt-btn + .kt-btn {
    margin-left: 0;
  }
}
</style>
