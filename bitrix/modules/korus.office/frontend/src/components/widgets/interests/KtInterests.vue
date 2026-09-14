<script lang="ts" setup>
import { ref, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useInterestsStore } from '@/stores/interests'
import { usePermissionsStore } from '@/stores/permissions'
import { useInterests } from '@/composables/interests/useInterests'

import { KtBtn, KtTagSelect } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitle, KtWidgetStub, KtInterestTag } from '@/components/lk'

import stubImage from '@/assets/widgets/interests/stub.png'

const preparedTags = ref<string[]>([])

const { mode, changeMode, add, remove, cancel, search } = useInterests(preparedTags)
const { INTERESTS, POPULAR } = storeToRefs(useInterestsStore())
const { CAN_EDIT } = storeToRefs(usePermissionsStore())

const tags = computed(() => {
  return INTERESTS.value.map(item => item.TEXT)
})
const tagsRecord = computed(() => {
  return INTERESTS.value.reduce((acc, interest) => {
    acc[interest.TEXT] = interest
    return acc
  }, Object.create(null))
})

const popularTags = computed(() => {
  return POPULAR.value.map(tip => tip.TEXT)
})
const popularTagsRecord = computed(() => {
  return POPULAR.value.reduce((acc, interest) => {
    acc[interest.TEXT] = interest
    return acc
  }, Object.create(null))
})

const isReadMode = computed(() => mode.value === 'read')
const isEditMode = computed(() => mode.value === 'edit')
const isStubMode = computed(() => mode.value === 'stub')
const isActionsVisible = computed(() => isEditMode.value || isStubMode.value)
</script>

<template>
  <kt-widget-wrapper>
    <article class="kt-interests">
      <header class="kt-interests__header">
        <kt-widget-title :text="$t('interests.title')" />

        <kt-btn v-if="isReadMode && CAN_EDIT"
                theme="tertiary"
                icon="pencil"
                round
                dense
                @click="changeMode('edit')"
        />
      </header>

      <div class="kt-interests__content">
        <div v-if="isReadMode" class="kt-interests__tags">
          <kt-interest-tag v-for="interest in INTERESTS"
                           :key="interest.TEXT"
                           :text="interest.TEXT"
                           :users="interest.USERS"
                           :href="`/company/?apply_filter=Y&TAGS=${interest.TEXT}`"
                           clickable
                           outline
          />
        </div>

        <kt-tag-select v-if="isEditMode"
                       v-model="preparedTags"
                       :tags="tags"
                       :title="`${$t('interests.popup.title')}:`"
                       :hint-popover-tags="popularTags"
                       :hint-popover-text="$t('interests.popup.description')"
                       class="kt-interests__tag-select"
                       @submit="add"
                       @tag:search="search"
                       @tag:remove="remove"
        >
          <template #popover-tag="{ tag, addPrepared }">
            <kt-interest-tag :text="tag"
                             :users="popularTagsRecord[tag]?.USERS"
                             outline
                             clickable
                             @click="addPrepared(tag)"
            />
          </template>

          <template #tag="{ tag, selected }">
            <kt-interest-tag :selected="selected[tag]"
                             :text="tag"
                             :users="tagsRecord[tag]?.USERS"
                             outline
                             removable
                             @remove="remove(tag)"
            />
          </template>

          <template #prepared-tag="{ tag, selected, removePrepared }">
            <kt-interest-tag :selected="selected[tag]"
                             :text="tag"
                             :users="tagsRecord[tag]?.USERS"
                             outline
                             removable
                             @remove="removePrepared(tag)"
            />
          </template>
        </kt-tag-select>

        <kt-widget-stub v-if="isStubMode"
                        :icon="stubImage"
                        :text="$t('interests.stub.description')"
        />
      </div>

      <footer v-if="isActionsVisible" class="kt-interests__footer">
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
.kt-interests {
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

  &__tags .kt-interest-tag {
    margin: 0;
    cursor: default !important;
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
