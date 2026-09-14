<script lang="ts" setup>
import { KtImg, KtTitle, KtBtn, KtBtnDropdown } from '@/components/shared'

interface IServiceButton {
  LABEL:    string;
  ICON:     string;
  URL:      string;
  OPTIONS?: {
    LABEL: string;
    URL:   string;
  }[];
}

interface IProps {
  title:   string;
  details: string;
  image:   string;
  color:   string;
  buttons: IServiceButton[];
}

const props = defineProps<IProps>()
</script>

<template>
  <article class="kt-favorite-service">
    <div class="kt-favorite-service__content">
      <a v-for="button in props.buttons"
         :key="button.URL"
         :href="button.URL"
         class="kt-favorite-service__icon-wrapper"
         :style="{ 'background-color': props.color }"
      >
        <kt-img v-if="props.image" :src="props.image" alt="" class="kt-favorite-service__icon"/>
      </a>

      <kt-title :text="props.title" :level="3" class="kt-favorite-service__title" />
    </div>

    <footer class="kt-favorite-service__actions">
      <component v-for="button in props.buttons"
                 :key="button.URL"
                 :is="button.OPTIONS ? KtBtnDropdown : KtBtn"
                 :label="button.LABEL"
                 :href="button.URL"
                 :icon-right="button.OPTIONS ? undefined : button.ICON"
                 :dropdown="button.OPTIONS"
                 theme="tertiary"
                 dense
                 target="_blank"
                 class="kt-service__action"
      >
        <q-list v-if="button.OPTIONS">
          <q-item v-for="option in button.OPTIONS"
                  :key="option.LABEL"
                  :href="option.URL"
                  clickable
                  v-close-popup
          >
            <q-item-section>
              <q-item-label>{{ option.LABEL.toUpperCase() }}</q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </component>
    </footer>
  </article>
</template>

<style lang="scss">
@use 'vars';

/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-favorite-service {
    display: flex;
    flex-direction: column;
    gap: var(--kt-favorite-service-offset);
    padding: var(--kt-favorite-service-padding);
    border-radius: var(--kt-favorite-service-border-radius);
    background-color: var(--kt-favorite-service-bg);

    &__icon-wrapper {
      display: block;
      width: var(--kt-favorite-service-icon-size);
      height: var(--kt-favorite-service-icon-size);
      margin: 0 auto;
      position: relative;
      border-radius: 50%;
    }

    &__icon {
      max-width: 50%;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);

      .q-img__image {
        height: auto;
        width: auto;
      }
    }

    &__title {
      --kt-title-font-size: var(--kt-favorite-service-title-font-size);

      margin-top: var(--kt-ui-offset-md);
      font-weight: var(--kt-favorite-service-title-font-weight);
      text-align: center;
    }

    &__actions {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 10px;
      margin-top: auto;
    }

    &__action {
      --kt-btn-margin-left: 0;
    }
  }
}
</style>
