<script lang="ts" setup>
import { storeToRefs } from 'pinia'
import { usePersonOrgStructureStore } from '@/stores/person-org-structure'
import { isEmpty } from '@/utils'

import { KtDetails, KtTree, KtSeparator } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitle } from '@/components/lk'
import { KtStructureUserList } from './components'

const { STRUCTURE, USERS, ownDivisions, ownDivisionDepths } = storeToRefs(usePersonOrgStructureStore())

function getNormalizedHead (head: string) {
  return [USERS.value[head]]
}

function getNormalizedTeam (team: number[]) {
  return team.map(id => {
    return USERS.value[id]
  })
}
</script>

<template>
  <kt-widget-wrapper>
    <div class="kt-structure">
      <header class="kt-structure__header">
        <kt-widget-title :text="$t('structure.title')" href="/company/vis_structure.php" />
      </header>

      <div class="kt-structure__content">
        <template v-for="(item, index) in STRUCTURE" :key="index">
          <kt-details :expand-text="$t('structure.details.expandText')"
                      :collapse-text="$t('structure.details.collapseText')"
          >
            <template #header>
              <kt-tree :nodes="item.TREE" default-expand-all />
              <kt-structure-user-list v-if="item.HEAD"
                                      :label="$t('personalInfo.director')"
                                      :users="getNormalizedHead(item.HEAD)"
              />
            </template>

            <kt-structure-user-list v-if="!isEmpty(item.TEAM)" :users="getNormalizedTeam(item.TEAM)" />
          </kt-details>

          <kt-separator v-if="index < STRUCTURE.length - 1" class="kt-structure__separator" />
        </template>
      </div>
    </div>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-structure {
  &__header {
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }

  .kt-details__header .kt-structure-user-list {
    margin-top: var(--kt-ui-offset-xl);
  }

  &__separator {
    margin-top: var(--kt-ui-offset-xl);
    margin-bottom: var(--kt-ui-offset-xl);
  }
}
</style>
