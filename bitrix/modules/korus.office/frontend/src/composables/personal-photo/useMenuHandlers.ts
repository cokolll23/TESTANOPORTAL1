import { storeToRefs } from 'pinia'
import { useI18n } from 'vue-i18n'
import { usePersonalStore } from 'stores/personal'
import { confirm } from '@/utils'

export function useMenuHandlers () {
  const { t } = useI18n()
  const store = usePersonalStore()
  const {
    STATUS,
    IS_FIRE_USER_ENABLED,
    ADMIN_RIGHTS_RESTRICTED,
    DELEGATE_ADMIN_RIGHTS_RESTRICTED
  } = storeToRefs(store)

  /**
   * Войти (выйти) из режима администратора
   */
  const setAdmin = async () => {
    await store.setAdmin()
  }
  const setAdminHandler = async () => {
    await setAdmin()
  }

  /**
   * Забрать права администратора
   */
  const removeAdminRights = async () => {
    await store.removeAdminRights()
  }
  const removeAdminRightsHandler = async () => {
    await removeAdminRights()
  }

  /**
   * Дать права администратора
   */
  const setAdminRights = async () => {
    await store.setAdminRights()
  }
  const setAdminRightsHandler = async () => {
    if (ADMIN_RIGHTS_RESTRICTED.value) {
      if (DELEGATE_ADMIN_RIGHTS_RESTRICTED.value) {
        top.BX.UI.InfoHelper.show('limit_admin_admins')
        return
      }

      try {
        await confirm({
          message: t('personalPhoto.menu.moveAdminRightsConfirm'),
          html: true
        })
        await setAdminRights()
      }
      catch (e) {}
    }
    else {
      await setAdminRights()
    }
  }

  /**
   * Уволить
   */
  const fireUser = async () => {
    await store.fireUser()
  }
  const fireUserHandler = async () => {
    if (!IS_FIRE_USER_ENABLED.value && STATUS.value !== 'integrator') {
      top.BX.UI.InfoHelper.show('limit_dismiss')
      return
    }

    try {
      await confirm({
        message: t('personalPhoto.menu.fireUserConfirm')
      })
      await fireUser()
    }
    catch (e) {}
  }
  const fireInvitedUserShowPopup = async () => {
    try {
      await confirm({
        message: t('personalPhoto.menu.fireInvitedUser'),
        html: true
      })
      await fireUser()
    }
    catch (e) {}
  }

  /**
   * Принять на работу
   */
  const hireUser = async () => {
    await store.hireUser()
  }
  const hireUserHandler = async () => {
    try {
      await confirm({
        message: t('personalPhoto.menu.hireUserConfirm')
      })
      await hireUser()
    }
    catch (e) {}
  }

  /**
   * Пригласить ещё раз
   */
  const reinviteUser = async () => {
    await store.reinviteUser()
  }
  const reinviteUserHandler = async () => {
    await reinviteUser()
  }

  /**
   * Удалить
   */
  const deleteUser = async () => {
    try {
      await store.deleteUser()
    }
    catch (e) {
      await fireInvitedUserShowPopup()
    }
  }
  const deleteUserHandler = async () => {
    try {
      await confirm({
        message: t('personalPhoto.menu.deleteUserConfirm')
      })
      await deleteUser()
    }
    catch (e) {}
  }

  /**
   * Перевести в интранет
   */
  const moveToIntranet = async () => {
    await store.moveToIntranet()
  }
  const moveToIntranetHandler = async () => {
    await moveToIntranet()
  }

  /**
   * Сделать интегратором
   */
  const setIntegratorRights = async () => {
    await store.setIntegratorRights()
  }
  const setIntegratorRightsHandler = async () => {
    try {
      await confirm({
        message: t('personalPhoto.menu.setIntegratorRightsConfirm')
      })
      await setIntegratorRights()
    }
    catch (e) {}
  }

  return {
    setAdminHandler,
    setAdminRightsHandler,
    removeAdminRightsHandler,
    fireUserHandler,
    hireUserHandler,
    reinviteUserHandler,
    deleteUserHandler,
    moveToIntranetHandler,
    setIntegratorRightsHandler
  }
}
