import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { usePermissionsStore } from 'stores/permissions'
import { usePersonalStore } from 'stores/personal'

export function useMenuItemsVisibility () {
  const {
    STATUS,
    IS_CURRENT_USER_INTEGRATOR,
    IS_EXTRANET_USER,
    IS_CLOUD
  } = storeToRefs(usePersonalStore())
  const {
    SHOW_SONET_ADMIN,
    CAN_EDIT,
    IS_OWN_PROFILE
  } = storeToRefs(usePermissionsStore())

  const isAdminModeItemVisible = computed(() => SHOW_SONET_ADMIN.value)
  const isRemoveAdminRightsItemVisible = computed(
    () => (
      STATUS.value === 'admin'
      && CAN_EDIT.value
      && !IS_OWN_PROFILE.value
    )
  )
  const isSetAdminRightsItemVisible = computed(
    () => (
      STATUS.value === 'employee'
      && CAN_EDIT.value
      && !IS_OWN_PROFILE.value
      && !IS_CURRENT_USER_INTEGRATOR.value
    )
  )
  const isFireUserItemVisible = computed(
    () => (
      STATUS.value === 'admin'
      || STATUS.value === 'employee'
      || STATUS.value === 'integrator'
      || IS_EXTRANET_USER.value
    )
    && CAN_EDIT.value
    && !IS_OWN_PROFILE.value
    && !['email', 'shop'].includes(STATUS.value)
  )
  const isHireUserItemVisible = computed(
    () => (
      STATUS.value === 'fired'
      && CAN_EDIT.value
      && !IS_OWN_PROFILE.value
    )
  )
  const isReinviteUserItemVisible = computed(
    () => (
      STATUS.value === 'invited'
      && CAN_EDIT.value
      && !IS_OWN_PROFILE.value
    )
  )
  const isMoveToIntranetItemVisible = computed(
    () => (
      IS_EXTRANET_USER.value
      && CAN_EDIT.value
      && !IS_OWN_PROFILE.value
      && IS_CLOUD.value
    )
  )
  const isSetIntegratorRightsItemVisible = computed(
    () => (
      IS_CLOUD.value
      && CAN_EDIT.value
      && !IS_OWN_PROFILE.value
      && STATUS.value !== 'integrator'
    )
  )

  return {
    isAdminModeItemVisible,
    isRemoveAdminRightsItemVisible,
    isSetAdminRightsItemVisible,
    isFireUserItemVisible,
    isHireUserItemVisible,
    isReinviteUserItemVisible,
    isMoveToIntranetItemVisible,
    isSetIntegratorRightsItemVisible
  }
}
