<template>
  <div class="kt-avatar-uploader" :class="layoutClasses">
    <q-avatar :color="avatar.bgColor">
      <q-img :src="avatar.src" ratio="1" />
    </q-avatar>

    <div v-if="canChangePhoto" class="kt-avatar-uploader__load-actions">
      <kt-button v-show="isCameraBtnVisible"
                 theme="primary"
                 class="kt-avatar-uploader__create-btn"
                 @click="createPhoto"
      >
        {{ $t('avatarUploader.createPhotoBtn') }}
      </kt-button>

      <kt-button theme="primary"
                 class="kt-avatar-uploader__upload-btn"
                 @click="uploadPhoto"
      >
        {{ $t('avatarUploader.uploadPhotoBtn') }}
      </kt-button>
    </div>

    <q-btn v-if="canDeletePhoto"
           flat
           unelevated
           padding="0"
           class="kt-avatar-uploader__remove-btn"
           @click="removePhoto"
    >
      <q-icon name="kt:close" size="12px" color="app-grey-8" />
    </q-btn>
  </div>
</template>

<script lang="ts" setup>
import { reactive, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { usePersonalStore } from 'stores/personal'
import { usePermissionsStore } from 'stores/permissions'
import { useAvatarUploader } from 'composables/avatar-uploader/useAvatarUploader'
import { KtButton } from 'components/old/button'
import { usePersonalFieldsStore } from 'stores/personal-fields'

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

<style lang="scss">
.pgk {
  .kt-avatar-uploader {
    $ns: &;
    $border-radius: 50%;

    display: flex;
    justify-content: center;
    align-items: center;
    flex-shrink: 0;
    position: relative;
    border-radius: $border-radius;
    white-space: nowrap;

    &::before {
      $width: 100%;
      $height: 100%;
      $top: 0%;
      $left: 0%;
      $border-radius: 50%;
      $opacity: 0;
      $z-index: 9;
      $background-image: linear-gradient(0deg, rgba($primary, 0.4), rgba($primary, 0.4));
      $transform: scale(0.5);
      $transition: transform 0.3s;

      content: '';
      position: absolute;
      width: $width;
      height: $height;
      top: $top;
      left: $left;
      transition: $transition;
      background-image: $background-image;
      border-radius: $border-radius;
      opacity: $opacity;
      z-index: $z-index;
      transform: $transform;
    }

    .q-avatar {
      $font-size: var(--avatar-size);

      font-size: $font-size;

      .q-img {
        $width: 100%;

        width: $width;
      }
    }

    &.is-edit-mode:hover::before {
      $transform: scale(1);
      $opacity: 1;

      opacity: $opacity;
      transform: $transform;
    }

    &__load-actions {
      $width: 100%;
      $opacity: 0;
      $z-index: 9;
      $transform: translateY(12px);
      $transition: 0.15s;

      width: $width;
      display: flex;
      flex-direction: column;
      align-items: center;
      position: absolute;
      opacity: $opacity;
      transform: $transform;
      transition: $transition;
      z-index: $z-index;
    }

    .kt-avatar-uploader.is-edit-mode:hover &__load-actions {
      $opacity: 1;
      $transform: translateY(0);

      opacity: $opacity;
      transform: $transform;
    }

    &__create-btn {
      $margin-bottom: 9px;

      margin-bottom: $margin-bottom;
    }

    &__remove-btn {
      $width: 22px;
      $height: 22px;
      $top: 20px;
      $right: 20px;
      $border-radius: 11px;
      $box-shadow: 0 1px 3px 0 rgb(0 0 0 / 10%);
      $background-color: var(--q-white);
      $z-index: 99;
      $opacity: 0;
      $transform: translateY(2px);
      $transition: 0.2s;

      width: $width;
      height: $height;
      position: absolute;
      top: $top;
      right: $right;
      box-shadow: $box-shadow;
      border-radius: $border-radius;
      opacity: $opacity;
      z-index: $z-index;
      background-color: $background-color;
      cursor: pointer;
      transition: $transition;
      transform: $transform;
    }

    .kt-avatar-uploader.is-edit-mode:hover &__remove-btn {
      $transform: translateY(0);
      $opacity: 1;

      transform: $transform;
      opacity: $opacity;
    }
  }

  /**
   * Стили попапа загрузки
   */
  .main-file-input-popup.popup-window-with-titlebar {
    $padding: 20px 20px 10px;

    padding: $padding;

    .popup-window-content {
      $padding: 0;
      $background-color: var(--q-white);

      padding: $padding;
      background-color: $background-color;
    }

    .popup-window-close-icon {
      $top: 30px;
      $right: 20px;

      top: $top;
      right: $right;

      &::after {
        background-image: url("data:image/svg+xml,%3Csvg width='10' height='10' viewBox='0 0 10 10' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M10 1.45 8.993.557 5 4.105 1.007.555 0 1.452 3.993 5 0 8.55l1.007.894L5 5.895l3.993 3.55L10 8.548 6.007 5 10 1.45Z' fill='%23A5ADB3'/%3E%3C/svg%3E");
      }
    }

    .popup-window-titlebar {
      $margin-bottom: 10px;

      margin-bottom: $margin-bottom;

      &-text {
        $padding-left: 0;
        $line-height: 30px;
        $color: var(--q-app-grey-3);

        padding-left: $padding-left;
        line-height: $line-height;
        color: $color;
      }
    }

    .main-file-input-tab-container {
      $padding: 15px;
      $border-radius: 4px;
      $background-color: var(--q-app-grey-17);

      padding: $padding;
      border-radius: $border-radius;
      background-color: $background-color;
    }

    .main-file-input-tab-avatar-image-item {
      $background-image: url("data:image/svg+xml,%3Csvg width='100' height='101' viewBox='0 0 100 101' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M83.59 78.406A43.719 43.719 0 1 0 6.25 50.5a43.434 43.434 0 0 0 10.16 27.906l-.063.053c.219.263.469.488.694.747.28.322.584.625.875.938.875.95 1.775 1.862 2.718 2.718.288.263.585.507.875.757 1 .862 2.029 1.681 3.094 2.444.138.093.263.215.4.312v-.037a43.441 43.441 0 0 0 50 0v.037c.138-.097.26-.219.4-.313a45.193 45.193 0 0 0 3.094-2.443c.29-.25.587-.497.875-.757a43.768 43.768 0 0 0 2.719-2.718c.29-.313.59-.616.875-.938.222-.26.475-.484.693-.75l-.068-.05ZM50 25.5a14.062 14.062 0 1 1 0 28.125A14.062 14.062 0 0 1 50 25.5ZM25.022 78.406A15.613 15.613 0 0 1 40.625 63h18.75a15.612 15.612 0 0 1 15.603 15.406 37.312 37.312 0 0 1-49.956 0Z' fill='%23C1C4C9'/%3E%3C/svg%3E");

      background-image: $background-image;
    }

    .main-file-input-tab-avatar-inner {
      $width: auto;

      width: $width;
    }

    .main-file-input-tab-avatar-image-container {
      $margin-bottom: 0;
      $padding-left: 30px;
      $text-align: left;

      margin-bottom: $margin-bottom;
      padding-left: $padding-left;
      text-align: $text-align;
    }

    .main-file-input-upload-block {
      $padding: 30px;
      $border-color: var(--q-app-grey-11);
      $border-radius: 4px;

      padding: $padding;
      border: 1px dashed $border-color;
      border-radius: $border-radius;
    }

    .main-file-input-upload-link-container {
      $top: 50%;
      $transform: translate(-50%,-50%);

      top: $top;
      transform: $transform;
    }

    .main-file-input-upload-link {
      $margin-bottom: 10px;
      $font: normal 16px/22px 'Open Sans', sans-serif;
      $border-bottom: none;
      $color: var(--q-secondary);
      $transition: color 0.3s ease-out;

      margin-bottom: $margin-bottom;
      font: $font;
      border-bottom: $border-bottom;
      color: $color;
      transition: $transition;

      &:hover {
        $border-bottom: none;
        $color: var(--q-primary);

        border-bottom: $border-bottom;
        color: $color;
      }
    }

    .main-file-input-arrow-icon {
      $width: 6px;
      $height: 12px;
      $background-image: url("data:image/svg+xml,%3Csvg width='6' height='13' viewBox='0 0 6 13' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M.08 10.786 3.78 6.5.08 2.214.82.5 6 6.5l-5.18 6-.74-1.714Z' fill='%23868D96'/%3E%3C/svg%3E");

      width: $width;
      height: $height;
      background-image: $background-image;

      &-container {
        $left: 10px;

        left: $left;
      }
    }

    .main-file-input-upload-desc {
      $font: normal 13px/18px 'Open Sans', sans-serif;
      $color: var(--q-app-grey-5);

      font: $font;
      color: $color;
    }

    .main-file-input-button {
      $height: 20px;
      $font-size: 8px;
      $line-height: 20px;
      $background-color: var(--q-primary);

      height: $height;
      font-size: $font-size;
      line-height: $line-height;
      background-color: $background-color;
    }

    .main-file-input-button-name {
      $margin: unset;
      $line-height: 20px;

      margin: $margin;
      line-height: $line-height;
    }

    .popup-window-buttons .popup-window-button {
      $transition: 0.3s ease-out;

      transition: $transition;

      &.popup-window-button-accept {
        $margin-right: 20px;
        $border-radius: 100px;

        --ui-btn-background: #{$secondary};
        --ui-btn-background-hover: #{$primary};
        --ui-btn-background-active: #{$primary};

        margin-right: $margin-right;
        border-radius: $border-radius;
      }

      &.popup-window-button-link.popup-window-button-link-cancel {
        $padding: 0;
        $color: var(--q-app-grey-8);

        padding: $padding;
        color: $color;

        &:hover {
          $color: var(--q-app-grey-3);

          color: $color;
        }
      }
    }
  }
}
</style>
