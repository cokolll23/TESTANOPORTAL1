<template>
  <div v-for="(item, index) in STRUCTURE" :key="index" class="kt-structure">
    <template v-if="isPopupMode(index)">
      <kt-widget-layout-row :label="$t('structure.division')">
        <kt-widget-layout-text color="primary" class="flex items-center">
          {{ ownDivisions[index] }}
          <q-btn flat round icon="kt:menu-round" color="secondary" size="12px" padding="none"/>
        </kt-widget-layout-text>

        <q-menu class="q-pa-md">
          <kt-tree :nodes="item.TREE" default-expand-all />
        </q-menu>
      </kt-widget-layout-row>
    </template>

    <template v-else>
      <kt-widget-layout-row>
        <kt-tree :nodes="item.TREE" default-expand-all />
      </kt-widget-layout-row>
    </template>

    <kt-widget-layout-row v-if="item.HEAD" :label="$t('personalInfo.director')" y-aligment="center" class="q-mb-none">
      <kt-user-card :user="USERS[item.HEAD]" size="md" />
    </kt-widget-layout-row>
  </div>
</template>

<script lang="ts" setup>
import { storeToRefs } from 'pinia'
import { usePersonOrgStructureStore } from 'stores/person-org-structure'

import { KtWidgetLayoutRow } from 'components/old/widget-layout-row'
import { KtTree } from 'components/old/tree'
import { KtUserCard } from 'components/old/user-card'
import { KtWidgetLayoutText } from 'components/old/widget-layout-text'

const { STRUCTURE, USERS, ownDivisions, ownDivisionDepths } = storeToRefs(usePersonOrgStructureStore())
const isPopupMode = (index: number) => ownDivisionDepths.value[index] > 2
</script>

<style lang="scss">
.pgk {
  .kt-structure + .kt-structure {
    $margin-top: 20px;

    margin-top: $margin-top;
  }

  .kt-structure {
    .kt-widget-layout-row__label {
      $min-width: 120px;

      min-width: $min-width;
    }
  }
}
</style>
