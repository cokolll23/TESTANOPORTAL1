<template>
  <kt-widget-layout :title="$t('gratitudes.title')"
                    actions-align="center"
                    class="kt-gratitudes"
  >
    <kt-gratitudes-list />
    <kt-gratitudes-post-list />

    <template #footer>
      <kt-button v-if="!IS_OWN_PROFILE" outline class="q-mx-auto" @click="openGratitudePage()">
        {{ $t('gratitudes.thanksBtn') }}
      </kt-button>
    </template>
  </kt-widget-layout>
</template>

<script lang="ts" setup>
import { storeToRefs } from 'pinia'
import { useGratitudes } from '@/composables/old/gratitudes/useGratitudes'
import { usePermissionsStore } from 'stores/permissions'

import { KtWidgetLayout } from 'components/old/widget-layout'
import { KtGratitudesList, KtGratitudesPostList } from './components'
import { KtButton } from 'components/old/button'

const { IS_OWN_PROFILE } = storeToRefs(usePermissionsStore())
const { openGratitudePage } = useGratitudes()
</script>
