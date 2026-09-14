<template>
  <div class="kt-personal-info-read-mode-skeleton">
    <div class="row full-width">
      <div class="column col-xs-12 col-lg-5 q-space relative-position">
        <q-skeleton type="QBtn"
                    width="70px"
                    height="15px"
                    class="q-ml-auto kt-personal-info-read-mode-skeleton__change-mode-btn"
        />

        <div>
          <div v-for="item in 4" :key="item" class="row no-wrap items-center q-mb-sm">
            <q-skeleton type="text" width="120px" class="q-mr-lg" />
            <q-skeleton type="text" class="full-width" />
          </div>
        </div>
      </div>

      <q-separator vertical color="app-grey-15" class="kt-personal-info-read-mode__vertical-separator" />

      <div class="col-xs-12 col-lg-5 q-space">
        <kt-structure-skeleton />
      </div>
    </div>

    <q-separator class="q-my-xs full-width" color="app-grey-15" />

    <footer class="q-pt-sm full-width">
      <q-skeleton type="QBtn" width="140px" height="15px" />
    </footer>
  </div>
</template>

<script lang="ts" setup>
import { KtStructureSkeleton } from 'components/old/structure'
</script>

<style lang="scss">
.pgk {
  .kt-personal-info-read-mode-skeleton {
    $width: 100%;

    width: $width;

    &__change-mode-btn {
      $top: -30px;
      $right: -15px;

      position: absolute;
      top: $top;
      right: $right;
    }

    &__vertical-separator {
      $margin: 0 30px 30px;

      margin: $margin;

      @media screen and (max-width: $breakpoint-md) {
        display: none;
      }
    }
  }
}
</style>
