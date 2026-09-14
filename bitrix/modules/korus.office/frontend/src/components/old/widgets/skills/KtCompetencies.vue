<template>
  <kt-widget-layout :title="$t('competencies.title')" padding-bottom="30px" class="kt-competencies">
    <template v-if="mode === 'read' && CAN_EDIT" v-slot:title-suffix>
      <kt-button theme="text" @click="changeMode('edit')">
        {{ $t('competencies.edit') }}
      </kt-button>
    </template>
    <div class="kt-competencies__items" v-if="mode === 'read'">
      <kt-button v-for="competence in items"
                 :key="competence.ID"
                 :href="`/company?apply_filter=Y&UF_COMPETENCE=${competence.ID}`"
                 theme="info"
                 outline
                 class="kt-competencies__item"
      >
        {{ competence.TITLE }}
      </kt-button>
    </div>
    <kt-widget-layout-stub v-if="mode === 'stub'"
                           icon="kt:competencies"
                           :text="$t('competencies.stub.description')"
                           :btn-text="$t('competencies.stub.btn')"
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
        <kt-tag v-for="competence in preparedTags"
                :key="competence"
                :text="competence"
                removable
                @remove-tag="removePrepared(competence)"
        />
        <q-menu v-model="isHintPopupVisible" no-focus class="kt-prepared-tags__popup">
          <template v-if="tips.length > 0">
            <kt-tag v-for="item in tips"
                    :key="item.TITLE"
                    :text="item.TITLE"
                    @click="addPrepared(item.TITLE)"
            />
            <q-separator color="app-grey-15" class="q-my-lg"/>
          </template>
          <kt-widget-layout-text color="app-grey-5">
            {{ $t('competencies.popup.description') }}
          </kt-widget-layout-text>
        </q-menu>
      </q-input>

      <div class="q-my-md">
        <kt-tag v-for="competence in items"
                :key="competence.ID"
                :text="competence.TITLE"
                removable
                @remove-tag="remove(competence)"
        />
      </div>

      <div class="d-flex justify-center items-center q-gutter-md">
        <kt-button theme="primary" @click="add">{{ $t('competencies.add') }}</kt-button>
        <kt-button theme="text" @click="cancel">{{ $t('competencies.cancel') }}</kt-button>
      </div>
    </div>
  </kt-widget-layout>
</template>

<script lang="ts" setup>
import { storeToRefs } from 'pinia'
import { useCompetenciesStore } from 'stores/competencies'
import { useCompetencies } from 'composables/old/competencies/useCompetencies'
import { usePreparedTags } from 'composables/old/usePreparedTags'
import { usePermissionsStore } from 'stores/permissions'

import { KtWidgetLayout } from 'components/old/widget-layout'
import { KtWidgetLayoutStub } from 'components/old/widget-layout-stub'
import { KtWidgetLayoutText } from 'components/old/widget-layout-text'
import { KtButton } from 'components/old/button'
import { KtTag } from 'components/old/tag'

const {
  newTagModel,
  preparedTags,
  isHintPopupVisible,
  addPrepared,
  removePrepared,
  showHintPopup
} = usePreparedTags()
const { mode, changeMode, add, remove, cancel, search } = useCompetencies(preparedTags, newTagModel)
const { items, tips } = storeToRefs(useCompetenciesStore())
const { CAN_EDIT } = storeToRefs(usePermissionsStore())
</script>

<style lang="scss">
.pgk {
  .kt-competencies {
    $margin-top: 10px;
    $margin-left: 10px;

    &__items {
      margin-top: $margin-top * -1;
      margin-left: $margin-left * -1;
    }

    &__item {
      margin-top: $margin-top;
      margin-left: $margin-left;
    }
  }
}
</style>
