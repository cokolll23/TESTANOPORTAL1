<script lang="ts" setup>
import { computed } from 'vue'
import { colors } from 'quasar'
import { storeToRefs } from 'pinia'
import { useI18n } from 'vue-i18n'
import { useRoute } from 'vue-router'
import { usePersonalStore } from '@/stores/personal'
import { useBadgesStore } from '@/stores/badges'
import { useVacationsStore } from '@/stores/current-vacation'
import { usePermissionsStore } from '@/stores/permissions'
import { useMenu } from '@/composables/personal-photo/useMenu'

import { KtBtn, KtBtnDropdown, KtIcon } from '@/components/shared'
import { KtWidgetWrapper, KtUserStatus, KtAvatarUploader, KtBadges } from '@/components/lk'

const route = useRoute()
const { STATUS, ONLINE_STATUS } = storeToRefs(usePersonalStore())
const { hasBadges } = storeToRefs(useBadgesStore())
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

function openChat () {
  BX.ready(function () {
    BX.Messenger.v2.Lib.Opener.openChat(route.params.id as string)
  })
}

function callTo () {
  BX.ready(function () {
    BX.Messenger.v2.Lib.Opener.startVideoCall(route.params.id as string, true)
  })
}
</script>

<template>
  <kt-widget-wrapper>
    <article class="kt-personal-photo">
      <header class="kt-personal-photo__header">
        <kt-btn-dropdown v-if="adminMenuBtnVisible"
                         theme="tertiary"
                         dense
                         :label="adminMenuBtnProps.text"
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
        </kt-btn-dropdown>

        <kt-user-status :is-online="ONLINE_STATUS.IS_ONLINE"
                        :text="ONLINE_STATUS.STATUS_TEXT"
                        class="kt-personal-photo__status"
        />
      </header>

      <div class="kt-personal-photo__avatar-wrapper">
        <kt-avatar-uploader />
        <kt-badges v-if="hasBadges" class="kt-personal-photo__badges" />
        <div v-if="VACATION_FORMATTED" class="kt-personal-photo__palm-icon-wr">
          <kt-icon name="palm" class="kt-personal-photo__palm-icon" />
        </div>
      </div>

      <div v-if="VACATION_FORMATTED" class="kt-personal-photo__vacation">
        {{ $t('personalPhoto.currentVacationLabel') }}:
        <span class="kt-personal-photo__vacation-period">{{ VACATION_FORMATTED }}</span>
      </div>

      <div v-if="!IS_OWN_PROFILE" class="kt-personal-photo__actions">
        <kt-btn theme="tertiary"
                dense
                :label="$t('personalPhoto.chatMessage')"
                @click="openChat"
        />
        <kt-btn theme="tertiary"
                dense
                :label="$t('personalPhoto.videoCall')"
                @click="callTo"
        />
      </div>
    </article>
  </kt-widget-wrapper>
</template>

<style lang="scss">
@use 'vars';

.kt-personal-photo {
  display: flex;
  flex-direction: column;

  &__header {
    display: flex;
    align-items: center;
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }

  &__status {
    margin-left: auto;
  }

  &__avatar-wrapper {
    width: var(--kt-personal-photo-avatar-size-mobile);
    height: var(--kt-personal-photo-avatar-size-mobile);
    position: relative;
    margin: 0 auto;

    @media screen and (min-width: 992px) {
      width: var(--kt-personal-photo-avatar-size-desktop);
      height: var(--kt-personal-photo-avatar-size-desktop);
    }
  }

  &__badges {
    position: absolute;
    top: 0;
    left: -9px;
    z-index: 10;
  }

  &__palm-icon-wr {
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    bottom: 0;
    right: 0;
    z-index: 10;
    width: var(--kt-personal-photo-palm-icon-size);
    height: var(--kt-personal-photo-palm-icon-size);
    border-radius: 50%;
    background-color: var(--kt-personal-photo-palm-icon-bg);

    @media screen and (min-width: 992px) {
      right: 24px;
    }
  }

  &__palm-icon {
    background-color: var(--kt-personal-photo-palm-icon-color);
  }

  &__vacation {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 24px;
    color: var(--kt-personal-photo-vacation-color);

    &-period {
      color: var(--kt-personal-photo-vacation-period-color);
    }
  }

  &__actions {
    display: block;
    text-align: center;
    font-size: 0;
    margin-top: var(--kt-ui-offset-xl);
    margin-bottom: var(--kt-ui-offset-lg);
  }
}
</style>
