<template>
  <kt-widget-layout :title="$t('interests.title')" padding-bottom="30px" class="kt-interests">
    <template v-if="mode === 'read' && CAN_EDIT" v-slot:title-suffix>
      <kt-button theme="text" @click="changeMode('edit')">
        {{ $t('interests.edit') }}
      </kt-button>
    </template>

    <div v-if="mode === 'read'">
      <kt-tag v-for="interest in INTERESTS"
              :key="interest.TEXT"
              :text="interest.TEXT"
              :users="interest.USERS"
              :href="`/company/?apply_filter=Y&TAGS=${interest.TEXT}`"
      />
    </div>

    <kt-widget-layout-stub v-if="mode === 'stub'"
                           icon="kt:hobbies"
                           :text="$t('interests.stub.description')"
                           :btn-text="$t('interests.stub.btn')"
                           @click="changeMode('edit')"
    />

    <div v-if="mode === 'edit'" class="full-width">
      <q-input v-model.trim="newTagModel"
               outlined
               class="kt-prepared-tags"
               @keydown.space="addPrepared()"
               @keydown.enter="add"
               @keydown.delete="removePrepared()"
               @keydown="showHintPopup()"
               @keyup="search(newTagModel)"
               @change="search(newTagModel)"
      >
        <kt-tag v-for="interest in preparedTags"
                :key="interest"
                :text="interest"
                removable
                @remove-tag="removePrepared(interest)"
        />

        <q-menu v-model="isHintPopupVisible" no-focus class="kt-prepared-tags__popup">
          <template v-if="POPULAR.length > 0">
            <kt-widget-layout-subtitle class="q-mb-md">
              {{ $t('interests.popup.title') }}:
            </kt-widget-layout-subtitle>

            <kt-tag v-for="interest in POPULAR"
                    :key="interest.TEXT"
                    :text="interest.TEXT"
                    :users="interest.USERS"
                    @click="addPrepared(interest.TEXT)"
            />

            <q-separator color="app-grey-15" class="q-my-lg"/>
          </template>

          <kt-widget-layout-text color="app-grey-5">
            {{ $t('interests.popup.description') }}
          </kt-widget-layout-text>
        </q-menu>
      </q-input>

      <div class="q-my-md">
        <kt-tag v-for="interest in INTERESTS"
                :key="interest.TEXT"
                :text="interest.TEXT"
                :users="interest.USERS"
                removable
                @remove-tag="remove(interest)"
        />
      </div>

      <div class="d-flex justify-center items-center q-gutter-md">
        <kt-button theme="primary" @click="add">{{ $t('interests.save') }}</kt-button>
        <kt-button theme="text" @click="cancel">{{ $t('interests.cancel') }}</kt-button>
      </div>
    </div>
  </kt-widget-layout>
</template>

<script lang="ts" setup>
import { storeToRefs } from 'pinia'
import { useInterests } from '@/composables/old/interests/useInterests'
import { usePreparedTags } from '@/composables/old/usePreparedTags'
import { useInterestsStore } from 'stores/interests'
import { usePermissionsStore } from 'stores/permissions'

import { KtWidgetLayout } from 'components/old/widget-layout'
import { KtWidgetLayoutStub } from 'components/old/widget-layout-stub'
import { KtWidgetLayoutSubtitle } from 'components/old/widget-layout-subtitle'
import { KtWidgetLayoutText } from 'components/old/widget-layout-text'
import { KtTag } from 'components/old/tag'
import { KtButton } from 'components/old/button'

const {
  newTagModel,
  preparedTags,
  isHintPopupVisible,
  addPrepared,
  removePrepared,
  showHintPopup
} = usePreparedTags()
const { mode, changeMode, add, remove, cancel, search } = useInterests(preparedTags, newTagModel)
const { INTERESTS, POPULAR } = storeToRefs(useInterestsStore())
const { CAN_EDIT } = storeToRefs(usePermissionsStore())
</script>

<style lang="scss">
</style>
