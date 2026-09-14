<script lang="ts" setup>
import { ref, computed } from 'vue'
import { useElementSize } from '@vueuse/core'
import { KtBtn, KtBtnDropdown, KtScrollArea } from '@/components/shared'
import { KtServiceTitle } from '@/components/widgets/service/components'

interface IServiceButton {
  LABEL:    string;
  ICON:     string;
  URL?:     string;
  OPTIONS?: {
    LABEL: string;
    HREF: string;
    TARGET: string;
  }[];
}

interface IProps {
  title:       string;
  image:       string;
  details:     string;
  color:       string;
  buttons:     IServiceButton[];
  titleSuffix: string;
}

const props = defineProps<IProps>()

const serviceContentRef = ref<null | HTMLElement>(null)
const serviceContentSize = useElementSize(serviceContentRef)

const scrollAreaMaxHeight = 150
const scrollAreaHeight = computed(() => {
  return Math.min(serviceContentSize.height.value, scrollAreaMaxHeight)
})
const scrollAreaStyles = computed(() => {
  return { height: scrollAreaHeight.value + 'px' }
})
</script>

<template>
  <article class="kt-service">
    <header class="kt-service__header">
      <kt-service-title :text="props.title" />

      <div v-if="props.titleSuffix">{{ props.titleSuffix }}</div>
    </header>

    <kt-scroll-area visible :style="scrollAreaStyles">
      <div v-html="props.details" ref="serviceContentRef" />
    </kt-scroll-area>

    <footer class="kt-service__actions">
      <component v-for="button in props.buttons"
                 :key="button.URL"
                 :is="button.OPTIONS ? KtBtnDropdown : KtBtn"
                 :label="button.LABEL"
                 :href="button.URL"
                 :icon-right="button.OPTIONS ? undefined : button.ICON"
                 theme="tertiary"
                 dense
                 target="_blank"
                 class="kt-service__action"
      >
        <q-list v-if="button.OPTIONS">
          <q-item v-for="option in button.OPTIONS"
                  :key="option.LABEL"
                  :href="option.HREF"
                  :target="option.TARGET"
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

.kt-service {
  display: flex;
  flex-direction: column;
  gap: var(--kt-service-offset);
  padding: var(--kt-service-padding);
  border-radius: var(--kt-service-border-radius);
  background-color: var(--kt-service-bg);

  &__header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: var(--kt-ui-offset-lg);
  }

  &__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: auto;
  }

  &__action {
    --kt-btn-margin-left: 0;
  }
}
</style>
