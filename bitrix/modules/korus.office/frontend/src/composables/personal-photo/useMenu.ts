import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useI18n } from 'vue-i18n'
import { usePermissionsStore } from 'stores/permissions'
import { useMenuItemsVisibility } from '@/composables/personal-photo/useMenuItemsVisibility'
import { useMenuHandlers } from '@/composables/personal-photo/useMenuHandlers'

export function useMenu () {
  const { t } = useI18n()
  const { IS_SESSION_ADMIN } = storeToRefs(usePermissionsStore())
  const {
    isAdminModeItemVisible,
    isRemoveAdminRightsItemVisible,
    isSetAdminRightsItemVisible,
    isFireUserItemVisible,
    isHireUserItemVisible,
    isReinviteUserItemVisible,
    isMoveToIntranetItemVisible,
    isSetIntegratorRightsItemVisible
  } = useMenuItemsVisibility()
  const {
    setAdminHandler,
    removeAdminRightsHandler,
    setAdminRightsHandler,
    fireUserHandler,
    hireUserHandler,
    reinviteUserHandler,
    deleteUserHandler,
    moveToIntranetHandler,
    setIntegratorRightsHandler
  } = useMenuHandlers()

  const menu = computed(() => {
    const menu = []

    if (isAdminModeItemVisible.value) {
      menu.push({
        text: IS_SESSION_ADMIN.value
          ? t('personalPhoto.menu.quitAdminMode')
          : t('personalPhoto.menu.adminMode'),
        handler: setAdminHandler
      })
    }

    if (isRemoveAdminRightsItemVisible.value) {
      menu.push({
        text: t('personalPhoto.menu.removeAdminRights'),
        handler: removeAdminRightsHandler
      })
    }

    if (isSetAdminRightsItemVisible.value) {
      menu.push({
        text: t('personalPhoto.menu.setAdminRights'),
        handler: setAdminRightsHandler
      })
    }

    if (isFireUserItemVisible.value) {
      menu.push({
        text: t('personalPhoto.menu.fireUser'),
        handler: fireUserHandler
      })
    }

    if (isHireUserItemVisible.value) {
      menu.push({
        text: t('personalPhoto.menu.hireUser'),
        handler: hireUserHandler
      })
    }

    if (isReinviteUserItemVisible.value) {
      menu.push({
        text: t('personalPhoto.menu.reinviteUser'),
        handler: reinviteUserHandler
      })

      menu.push({
        text: t('personalPhoto.menu.deleteUser'),
        handler: deleteUserHandler
      })
    }

    if (isMoveToIntranetItemVisible.value) {
      menu.push({
        text: t('personalPhoto.menu.moveToIntranet'),
        handler: moveToIntranetHandler
      })
    }

    if (isSetIntegratorRightsItemVisible.value) {
      menu.push({
        text: t('personalPhoto.menu.setIntegratorRights'),
        handler: setIntegratorRightsHandler
      })
    }

    return menu
  })

  return {
    menu
  }
}
