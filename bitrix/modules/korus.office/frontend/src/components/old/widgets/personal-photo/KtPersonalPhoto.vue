<template>
  <div class="kt-personal-photo">
    <div class="flex items-center justify-between q-mb-md">
      <kt-button v-if="adminMenuBtnVisible"
                 theme="none"
                 :label="adminMenuBtnProps.text"
                 :dropdown="CAN_EDIT && menu.length > 0"
                 :style="adminMenuBtnStyles"
                 class="kt-personal-photo__admin-menu-btn"
      >
        <q-list v-if="menu.length > 0">
          <q-item v-for="item in menu"
                  :key="item.text"
                  clickable
                  v-close-popup
                  @click="item.handler"
          >
            <q-item-section>
              <q-item-label>{{ item.text }}</q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </kt-button>

      <kt-user-status :is-online="ONLINE_STATUS.IS_ONLINE"
                      :text="ONLINE_STATUS.STATUS_TEXT"
                      class="kt-personal-photo__status q-ml-auto"
      />
    </div>

    <div class="kt-personal-photo__avatar-wrapper relative-position q-mx-auto">
      <kt-avatar-uploader class="q-mx-auto" />
      <kt-badges />
    </div>

    <div v-if="VACATION_FORMATTED" class="kt-personal-photo__current-vacation">
      <kt-icon name="kt:palm" color="white" size="lg" background-color="app-orange-3" />
        <kt-personal-info-text :label="$t('personalPhoto.currentVacationLabel')"
                               :content="VACATION_FORMATTED"
                               class="q-mb-none"
        />
    </div>
    <template v-if="IS_OWN_PROFILE || VACATION_FORMATTED">
      <q-separator color="app-grey-15" class="q-mt-md" />

    <kt-personal-info-user :label="`${$t('personalPhoto.replacementStaff')}:`"
                           :user="VACATION.DEPUTY.map(id => DEPUTY_USERS[id])"
                           justify="center"
                           class-list="content-flex"
                           multiple
                           class="column justify-center q-mb-none replacement-staff"
    >
      <template #footer>
        <kt-button
          v-if="EDIT_DEPUTY"
          href="/lk/deputy/"
          theme="primary"
          unelevated
          rounded
          style="margin-top: 15px"
        >{{ $t('personalPhoto.replacementStaffAdd') }}</kt-button>
      </template>
    </kt-personal-info-user>
    </template>

    <div v-if="!IS_OWN_PROFILE" class="kt-personal-photo__btn">
      <kt-button @click="openChat" class="ui-btn ui-btn-sm ui-btn-light-border ui-btn-round">{{ $t('personalPhoto.chatMessage') }}</kt-button>
      <kt-button @click="callTo" class="ui-btn ui-btn-sm ui-btn-light-border ui-btn-round">{{ $t('personalPhoto.videoCall') }}</kt-button>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { colors } from 'quasar'

import { storeToRefs } from 'pinia'
import { useI18n } from 'vue-i18n'
import { useRoute } from 'vue-router'
import { usePersonalStore } from 'stores/personal'
import { useVacationsStore } from 'stores/current-vacation'
import { usePermissionsStore } from 'stores/permissions'
import { useMenu } from '@/composables/personal-photo/useMenu'

import { KtUserStatus } from 'components/old/user-status'
import { KtPersonalInfoText, KtPersonalInfoUser } from 'components/old/personal-info-views/read'
import { KtAvatarUploader } from 'components/old/avatar-uploader'
import { KtBadges } from 'components/old/badges'
import { KtIcon } from 'components/old/icon'
import { KtButton } from 'components/old/button'

const route = useRoute()
const { STATUS, ONLINE_STATUS } = storeToRefs(usePersonalStore())
const { CAN_EDIT, IS_OWN_PROFILE, SHOW_SONET_ADMIN, EDIT_DEPUTY } = storeToRefs(usePermissionsStore())
const { VACATION, VACATION_FORMATTED, USERS: DEPUTY_USERS } = storeToRefs(useVacationsStore())
const { t } = useI18n()
const { menu } = useMenu()

const { getPaletteColor } = colors

const adminMenuBtnVisible = computed(() => {
  return (
    !!STATUS.value
    && (
      !(STATUS.value === 'employee' && (IS_OWN_PROFILE.value || !CAN_EDIT.value))
      || SHOW_SONET_ADMIN.value
    )
  )
})
const adminMenuBtnProps = computed(() => {
  const props = {
    text: t(`personalPhoto.status.${STATUS.value}`),
    color: getPaletteColor('white'),
    background: ''
  }

  switch (STATUS.value) {
    case 'admin':
      props.background = getPaletteColor('primary')
      break
    case 'integrator':
      props.background = getPaletteColor('app-blue-2')
      break
    case 'extranet':
      props.background = getPaletteColor('app-orange-1')
      break
    case 'fired':
      props.background = getPaletteColor('app-grey-8')
      break
    case 'invited':
      props.background = getPaletteColor('app-green-1')
      break
    case 'email':
    case 'shop':
    case 'visitor':
      props.background = getPaletteColor('app-grey-5')
      break
    case 'employee':
      props.color = getPaletteColor('app-grey-5')
      props.background = 'transparent'
      break
  }

  return props
})
const adminMenuBtnStyles = computed(() => ({
  color: adminMenuBtnProps.value.color,
  backgroundColor: adminMenuBtnProps.value.background,
  cursor: CAN_EDIT.value ? 'pointer' : ''
}))

const openChat = () => {
  window.BX.ready(function () {
    window.BXIM.openMessenger(route.params.id)
  })
}
const callTo = () => {
  window.BX.ready(function () {
    window.BXIM.callTo(route.params.id)
  })
}
</script>

<style lang="scss">
.pgk {
  .kt-personal-photo {
    $padding: 20px;
    $border-radius: 10px;
    $background-color: var(--q-app-white);

    display: flex;
    flex-direction: column;
    padding: $padding;
    border-radius: $border-radius;
    background-color: $background-color;

    &__admin-menu-btn {
      $min-height: 20px;
      $height: 20px;
      $padding: 0 18px;
      $font-size: 10px;

      min-height: $min-height;
      height: $height;
      padding: $padding;
      font-size: $font-size;
    }

    &__status {
      $margin-bottom: 6px;

      margin-bottom: $margin-bottom;
    }

    &__avatar-wrapper {
      $width: var(--avatar-size);
      $height: var(--avatar-size);

      width: $width;
      height: $height;
    }

    &__avatar {
      $margin: 0 auto;

      margin: $margin;
    }

    &__btn {
      display: block;
      text-align: center;
      margin-bottom: 14px;
      font-size: 0;
      margin-top: 25px;
    }

    &__current-vacation {
      $margin: 16px auto 0;

      margin: $margin;
      position: relative;

      & > .kt-icon-wrapper {
        $top: -58px;
        $right: 32px;

        position: absolute;
        top: $top;
        right: $right;
      }
    }

    .kt-widget-layout-row__label {
      $width: auto;
      $margin-right: 10px;

      width: $width;
      margin-right: $margin-right;
    }
  }
  .replacement-staff {
    margin-top: 10px!important;
  }
  @media screen and (max-width: $breakpoint-xs) {
    .replacement-staff {
      margin-right: 0px;
    }
  }
  .content-flex {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
}
</style>
