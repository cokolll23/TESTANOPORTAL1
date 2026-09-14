<template>
  <kt-widget-layout-row :label="props.label">
    <kt-widget-layout-text v-if="props.isEmpty" :color="props.color">
      <slot>
        {{ props.content }}
      </slot>

      <q-icon v-if="props.private"
              :name="eyeStatus ? 'kt:eye-slashed' : 'kt:eye'"
              size="16px"
              color="primary"
              class="q-ml-sm private-toggle-icon"
              style="cursor: pointer"
              @click="toggleVisibility"
      />
    </kt-widget-layout-text>
    <template v-else>
      <div class="kt-widget-layout-row">
        <div class="kt-widget-layout-text kt-widget-layout-row__label">
          Офис:
        </div>
        <div class="kt-widget-layout-row__content">
          {{ props.content.CITY }}
        </div>
      </div>
      <div class="kt-widget-layout-row">
        <div class="kt-widget-layout-text kt-widget-layout-row__label">
          Этаж:
        </div>
        <div class="kt-widget-layout-row__content">
          {{ props.content.FLOOR }}
        </div>
      </div>
      <div class="kt-widget-layout-row">
        <div class="kt-widget-layout-text kt-widget-layout-row__label">
          Номер рабочего места:
        </div>
        <div class="kt-widget-layout-row__content">
          {{ props.content.NUMBER }}
        </div>
      </div>
      <div class="text-right">
        <a :href="props.content.URL">Показать на карте</a>
      </div>
    </template>
  </kt-widget-layout-row>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { KtWidgetLayoutRow } from 'components/old/widget-layout-row'
import { KtWidgetLayoutText } from 'components/old/widget-layout-text'

import { usePersonalFieldsStore } from 'stores/personal-fields'

const store = usePersonalFieldsStore()

interface IKtPersonalInfoWorkplace {
  CITY: string;
  FLOOR: string;
  NUMBER: string;
  URL: string;
}

interface IKtPersonalInfoWorkplaceProps {
  label: string;
  content: IKtPersonalInfoWorkplace;
  color: string;
  mapLink: string;
  isEmpty: boolean;
  fieldCode: string;
  private: boolean;
  hidden: boolean;
}

const props = defineProps<IKtPersonalInfoWorkplaceProps>()

const eyeStatus: any = ref(props.hidden)

const toggleVisibility = () => {
  const formData = new FormData()
  formData.set('fieldCode', String(props.fieldCode))
  formData.set('value', String(eyeStatus.value ? 0 : 1))

  store.changeVisibility(formData)
  eyeStatus.value = !eyeStatus.value
}
</script>

<style lang="scss">
.pgk {
  .kt-personal-info-workplace {
    display: flex;
    align-items: center;

    & > a {
      $margin-left: 10px;
      $font-size: 14px;
      $line-height: 19px;
      $color: var(--q-app-grey-5);
      $transition: color 0.3s ease-out;

      margin-left: $margin-left;
      position: relative;
      font-size: $font-size;
      line-height: $line-height;
      color: $color;
      transition: $transition;

      &:hover,
      &:focus {
        $color: var(--q-secondary);

        color: $color;

        &::before {
          $background-color: var(--q-secondary);

          background-color: $background-color;
        }
      }

      &::before {
        $width: 100%;
        $height: 1px / 3;
        $bottom: 4px;
        $left: 0;
        $background-color: var(--q-app-grey-5);
        $transition: color 0.3s ease-out;

        content: '';
        width: $width;
        height: $height;
        display: block;
        position: absolute;
        left: $left;
        bottom: $bottom;
        background-color: $background-color;
        transition: $transition;
      }
    }
  }
}
</style>
