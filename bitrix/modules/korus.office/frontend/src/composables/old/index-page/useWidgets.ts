import { ref, computed, type Component, markRaw, ComputedRef } from 'vue'
import { useRootStore } from 'stores/root'
import { Screen } from 'quasar'
import { ObjectKeys } from '@/utils'

export interface IPageWidget {
  name:              string;
  component:         Component;
  componentSkeleton: Component;
  isVisible:         ComputedRef<boolean>;
  isSkeletonVisible: boolean;
}

export function useOldWidgets () {
  const rootStore = useRootStore()

  const widgetsMap = import.meta.glob('@/components/old/widgets/**/*.vue')
  const widgets = ref<Record<string, Component>>({})

  const leftColumnWidgets = ref<IPageWidget[]>([])
  const rightColumnWidgets = ref<IPageWidget[]>([])
  const mobileColumnWidgets = ref<IPageWidget[]>([])

  loadWidgets()

  async function loadWidgets () {
    return Promise
      .all(ObjectKeys(widgetsMap).map(path => {
        return widgetsMap[path]()
      }))
      .then(data => {
        data.forEach(widget => {
          widgets.value[widget.default.__name] = widget.default
        })
      })
      .then(() => {
        setLeftColumns()
        setRightColumns()
        setMobileColumn()
      })
  }

  function setLeftColumns () {
    leftColumnWidgets.value = [
      {
        name: 'kt-personal-photo',
        component: computed(() => markRaw(widgets.value.KtPersonalPhoto)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtPersonalPhotoSkeleton)),
        isVisible: computed(() => rootStore.modules.PERSONAL.isVisible),
        isSkeletonVisible: true
      },
      {
        name: 'kt-workplace',
        component: computed(() => markRaw(widgets.value.KtWorkplace)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtWorkplaceSkeleton)),
        isVisible: computed(() => rootStore.modules.WORKPLACE.isVisible),
        isSkeletonVisible: false
      },
      {
        name: 'kt-shop',
        component: computed(() => markRaw(widgets.value.KtShop)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtShopSkeleton)),
        isVisible: computed(() => rootStore.modules.SHOP.isVisible),
        isSkeletonVisible: true
      },
      {
        name: 'kt-skills',
        component: computed(() => markRaw(widgets.value.KtCompetencies)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtCompetenciesSkeleton)),
        isVisible: computed(() => rootStore.modules.COMPETENCIES.isVisible),
        isSkeletonVisible: true
      },
      {
        name: 'kt-gratitudes',
        component: computed(() => markRaw(widgets.value.KtGratitudes)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtGratitudesSkeleton)),
        isVisible: computed(() => rootStore.modules.GRATITUDES.isVisible),
        isSkeletonVisible: true
      }
    ]
  }

  function setRightColumns () {
    rightColumnWidgets.value = [
      {
        name: 'kt-personal-info',
        component: computed(() => markRaw(widgets.value.KtPersonalInfo)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtPersonalInfoSkeleton)),
        isVisible: computed(() => rootStore.modules.PERSONAL.isVisible),
        isSkeletonVisible: true
      },
      {
        name: 'kt-services',
        component: computed(() => markRaw(widgets.value.KtServices)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtServicesSkeleton)),
        isVisible: computed(() => rootStore.modules.SERVICES.isVisible),
        isSkeletonVisible: true
      },
      {
        name: 'kt-requests',
        component: computed(() => markRaw(widgets.value.KtRequests)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtRequestsSkeleton)),
        isVisible: computed(() => rootStore.modules.REQUESTS.isVisible),
        isSkeletonVisible: true
      },
      {
        name: 'kt-about-me',
        component: computed(() => markRaw(widgets.value.KtAboutMe)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtAboutMeSkeleton)),
        isVisible: computed(() => rootStore.modules.ABOUT.isVisible),
        isSkeletonVisible: true
      },
      {
        name: 'kt-interests',
        component: computed(() => markRaw(widgets.value.KtInterests)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtInterestsSkeleton)),
        isVisible: computed(() => rootStore.modules.INTERESTS.isVisible),
        isSkeletonVisible: true
      }
    ]
  }

  function setMobileColumn () {
    mobileColumnWidgets.value = [
      {
        name: 'kt-personal-photo',
        component: computed(() => markRaw(widgets.value.KtPersonalPhoto)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtPersonalPhotoSkeleton)),
        isVisible: computed(() => rootStore.modules.PERSONAL.isVisible),
        isSkeletonVisible: true
      },
      {
        name: 'kt-personal-info',
        component: computed(() => markRaw(widgets.value.KtPersonalInfo)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtPersonalInfoSkeleton)),
        isVisible: computed(() => rootStore.modules.PERSONAL.isVisible),
        isSkeletonVisible: true
      },
      {
        name: 'kt-services',
        component: computed(() => markRaw(widgets.value.KtServices)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtServicesSkeleton)),
        isVisible: computed(() => rootStore.modules.SERVICES.isVisible),
        isSkeletonVisible: true
      },
      {
        name: 'kt-requests',
        component: computed(() => markRaw(widgets.value.KtRequests)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtRequestsSkeleton)),
        isVisible: computed(() => rootStore.modules.REQUESTS.isVisible),
        isSkeletonVisible: true
      },
      {
        name: 'kt-about-me',
        component: computed(() => markRaw(widgets.value.KtAboutMe)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtAboutMeSkeleton)),
        isVisible: computed(() => rootStore.modules.ABOUT.isVisible),
        isSkeletonVisible: true
      },
      {
        name: 'kt-interests',
        component: computed(() => markRaw(widgets.value.KtInterests)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtInterestsSkeleton)),
        isVisible: computed(() => rootStore.modules.INTERESTS.isVisible),
        isSkeletonVisible: true
      },
      {
        name: 'kt-workplace',
        component: computed(() => markRaw(widgets.value.KtWorkplace)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtWorkplaceSkeleton)),
        isVisible: computed(() => rootStore.modules.WORKPLACE.isVisible),
        isSkeletonVisible: false
      },
      {
        name: 'kt-shop',
        component: computed(() => markRaw(widgets.value.KtShop)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtShopSkeleton)),
        isVisible: computed(() => rootStore.modules.SHOP.isVisible),
        isSkeletonVisible: true
      },
      {
        name: 'kt-skills',
        component: computed(() => markRaw(widgets.value.KtCompetencies)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtCompetenciesSkeleton)),
        isVisible: computed(() => rootStore.modules.COMPETENCIES.isVisible),
        isSkeletonVisible: true
      },
      {
        name: 'kt-gratitudes',
        component: computed(() => markRaw(widgets.value.KtGratitudes)),
        componentSkeleton: computed(() => markRaw(widgets.value.KtGratitudesSkeleton)),
        isVisible: computed(() => rootStore.modules.GRATITUDES.isVisible),
        isSkeletonVisible: true
      }
    ]
  }

  return {
    leftColumnWidgets,
    rightColumnWidgets,
    mobileColumnWidgets
  }
}
