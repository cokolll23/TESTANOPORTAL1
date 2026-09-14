<script lang="ts" setup>
import { onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { usePersonalStore } from '@/stores/personal'
import { KtWidgetWrapper } from '@/components/lk'

const { FULL_NAME, WORK_POSITION } = storeToRefs(usePersonalStore())

onMounted(() => {
  document.dispatchEvent(new CustomEvent('PersonalCommonMounted'))
})
</script>

<template>
  <kt-widget-wrapper>
    <article class="kt-personal-common">
      <div class="kt-personal-common__top">
        <span class="kt-personal-common__name">{{ FULL_NAME }}</span>
        <div class="kt-personal-common__status" id="personal-status"></div>
      </div>

      <div class="kt-personal-common__bottom">
        <span class="kt-personal-common__position">{{ WORK_POSITION }}</span>
      </div>
    </article>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-personal-common {
  display: flex;
  flex-wrap: wrap;
  gap: var(--kt-ui-offset-md);

  &__top,
  &__bottom {
    width: 100%;
  }

  &__top {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: var(--kt-ui-offset-lg);
  }

  &__name {
    font-family: var(--ui-font-family-secondary);
    font-size: 16px;
    line-height: 1.375;
    font-weight: 700;
    color: var(--kt-ui-text-primary);

    @media screen and (min-width: 768px) {
      font-size: 20px;
    }
  }

  &__status {
    display: inline-flex;
    align-items: center;
    gap: 10px;
  }

  &__position {
    font-size: 14px;
    line-height: 1.375;
    color: var(--kt-ui-text-secondary);

    @media screen and (min-width: 768px) {
      font-size: 16px;
    }
  }
}
</style>
