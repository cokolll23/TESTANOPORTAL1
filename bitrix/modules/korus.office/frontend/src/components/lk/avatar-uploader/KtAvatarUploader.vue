<script lang="ts" setup>
import { reactive, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { usePersonalStore } from '@/stores/personal'
import { usePermissionsStore } from '@/stores/permissions'
import { usePersonalFieldsStore } from '@/stores/personal-fields'
import { useAvatarUploader } from '@/composables/avatar-uploader/useAvatarUploader'

import { KtAvatar, KtImg, KtBtn } from '@/components/shared'

const {
  isCameraBtnVisible,
  createPhoto,
  uploadPhoto,
  removePhoto
} = useAvatarUploader()
const { AVATAR, IS_AVATAR_DEFAULT } = storeToRefs(usePersonalStore())
const { CAN_EDIT, IS_OWN_PROFILE } = storeToRefs(usePermissionsStore())
const fieldsStore = usePersonalFieldsStore()
const { FIELDS } = storeToRefs(fieldsStore)

const photoField = computed(() => (
  FIELDS.value.find(field => field.name === 'PERSONAL_PHOTO')
))

const canChangePhoto = computed(() => (IS_OWN_PROFILE.value || CAN_EDIT.value) && photoField?.value.editable)
const layoutClasses = computed(() => ([
  canChangePhoto.value ? 'is-edit-mode' : ''
]))
const avatar = reactive({
  src: computed(() => AVATAR.value),
  bgColor: computed(() => AVATAR.value === null ? 'app-grey-13' : '')
})
const canDeletePhoto = computed(() => canChangePhoto.value && !IS_AVATAR_DEFAULT.value)
</script>

<template>
  <div class="kt-avatar-uploader" :class="layoutClasses">
    <kt-avatar :color="avatar.bgColor">
      <kt-img :src="avatar.src" ratio="1" />
    </kt-avatar>

    <div v-if="canChangePhoto" class="kt-avatar-uploader__load-actions">
      <kt-btn v-show="isCameraBtnVisible"
              theme="primary"
              :label="$t('avatarUploader.createPhotoBtn')"
              class="kt-avatar-uploader__create-btn"
              @click="createPhoto"
      />

      <kt-btn theme="primary"
              :label="$t('avatarUploader.uploadPhotoBtn')"
              class="kt-avatar-uploader__upload-btn"
              @click="uploadPhoto"
      />
    </div>

    <kt-btn v-if="canDeletePhoto"
            theme="secondary"
            icon="close"
            round
            dense
            class="kt-avatar-uploader__remove-btn"
            @click="removePhoto"
    />
  </div>
</template>

<style lang="scss">
.kt-avatar-uploader {
  $ns: &;

  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  border-radius: 50%;
  white-space: nowrap;

  &::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background-image: linear-gradient(0deg, rgba(from var(--kt-ui-color-primary) r g b / 40%), rgba(from var(--kt-ui-color-primary) r g b / 40%));
    border-radius: 50%;
    opacity: 0;
    z-index: 9;
    transform: scale(0.5);
    transition: transform 160ms;
  }

  .q-avatar {
    font-size: var(--kt-personal-photo-avatar-size-mobile);

    @media screen and (min-width: 992px) {
      font-size: var(--kt-personal-photo-avatar-size-desktop);
    }
  }

  .q-avatar .q-img {
    width: 100%;
  }

  &.is-edit-mode:hover::before {
    opacity: 1;
    transform: scale(1);
  }

  &__load-actions {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: absolute;
    opacity: 0;
    z-index: 9;
    transform: translateY(12px);
    transition: 160ms;

    #{$ns}.is-edit-mode:hover & {
      opacity: 1;
      transform: translateY(0);
    }
  }

  &__create-btn {
    margin-bottom: 9px;
  }

  &__remove-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 20;
    opacity: 0;
    box-shadow: var(--kt-ui-box-shadow-base);

    #{$ns}.is-edit-mode:hover & {
      opacity: 1;
    }

    @media screen and (min-width: 992px) {
      right: 20px;
    }
  }
}

/**
 * Стили попапа загрузки
 */
.main-file-input-popup.popup-window-with-titlebar {
  padding: 20px 20px 10px;

  .popup-window-content {
    padding: 0;
    background-color: var(--q-white);
  }

  .popup-window-close-icon {
    top: 30px;
    right: 20px;

    &::after {
      background-image: url("data:image/svg+xml,%3Csvg width='10' height='10' viewBox='0 0 10 10' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M10 1.45 8.993.557 5 4.105 1.007.555 0 1.452 3.993 5 0 8.55l1.007.894L5 5.895l3.993 3.55L10 8.548 6.007 5 10 1.45Z' fill='%23A5ADB3'/%3E%3C/svg%3E");
    }
  }

  .popup-window-titlebar {
    margin-bottom: 10px;

    &-text {
      padding-left: 0;
      line-height: 30px;
      color: var(--q-app-grey-3);
    }
  }

  .main-file-input-tab-container {
    padding: 15px;
    border-radius: 4px;
    background-color: var(--q-app-grey-17);
  }

  .main-file-input-tab-avatar-image-item {
    background-image: url("data:image/svg+xml,%3Csvg width='100' height='101' viewBox='0 0 100 101' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M83.59 78.406A43.719 43.719 0 1 0 6.25 50.5a43.434 43.434 0 0 0 10.16 27.906l-.063.053c.219.263.469.488.694.747.28.322.584.625.875.938.875.95 1.775 1.862 2.718 2.718.288.263.585.507.875.757 1 .862 2.029 1.681 3.094 2.444.138.093.263.215.4.312v-.037a43.441 43.441 0 0 0 50 0v.037c.138-.097.26-.219.4-.313a45.193 45.193 0 0 0 3.094-2.443c.29-.25.587-.497.875-.757a43.768 43.768 0 0 0 2.719-2.718c.29-.313.59-.616.875-.938.222-.26.475-.484.693-.75l-.068-.05ZM50 25.5a14.062 14.062 0 1 1 0 28.125A14.062 14.062 0 0 1 50 25.5ZM25.022 78.406A15.613 15.613 0 0 1 40.625 63h18.75a15.612 15.612 0 0 1 15.603 15.406 37.312 37.312 0 0 1-49.956 0Z' fill='%23C1C4C9'/%3E%3C/svg%3E");
  }

  .main-file-input-tab-avatar-inner {
    width: auto;
  }

  .main-file-input-tab-avatar-image-container {
    margin-bottom: 0;
    padding-left: 30px;
    text-align: left;
  }

  .main-file-input-upload-block {
    padding: 30px;
    border-color: var(--q-app-grey-11);
    border-radius: 4px;
  }

  .main-file-input-upload-link-container {
    top: 50%;
    transform: translate(-50%,-50%);
  }

  .main-file-input-upload-link {
    margin-bottom: 10px;
    font-style: normal;
    font-size: 16px;
    line-height: 1.375;
    border-bottom: none;
    color: var(--q-secondary);
    transition: color 160ms ease-out;

    &:hover {
      border-bottom: none;
      color: var(--q-primary);
    }
  }

  .main-file-input-arrow-icon {
    width: 6px;
    height: 12px;
    background-image: url("data:image/svg+xml,%3Csvg width='6' height='13' viewBox='0 0 6 13' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M.08 10.786 3.78 6.5.08 2.214.82.5 6 6.5l-5.18 6-.74-1.714Z' fill='%23868D96'/%3E%3C/svg%3E");

    &-container {
      left: 10px;
    }
  }

  .main-file-input-upload-desc {
    font-style: normal;
    font-size: 13px;
    line-height: 1.38;
    color: var(--q-app-grey-5);
  }

  .main-file-input-button {
    height: 20px;
    font-size: 8px;
    line-height: 20px;
    background-color: var(--q-primary);
  }

  .main-file-input-button-name {
    margin: unset;
    line-height: 20px;
  }

  .popup-window-buttons .popup-window-button {
    transition: 160ms ease-out;

    &.popup-window-button-accept {
      margin-right: 20px;
      border-radius: 100px;
    }

    &.popup-window-button-link.popup-window-button-link-cancel {
      padding: 0;
      color: var(--q-app-grey-8);

      &:hover {
        color: var(--q-app-grey-3);
      }
    }
  }
}
</style>
