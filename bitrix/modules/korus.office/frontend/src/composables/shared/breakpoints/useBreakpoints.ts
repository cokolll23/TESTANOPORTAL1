import { useWindowSize } from '@vueuse/core'

export function useBreakpoints () {
  interface Breakpoints {
    mobile: number;
    tablet: number;
    desktop: number;
  }

  const defaultBreakpoints: Breakpoints = {
    mobile: 768, // mobile is from 360px up to 767px
    tablet: 1280, // tablet is from 768px up to 1279px
    desktop: 1440 // small desktop is from 1280px up to 1439px
    // large desktop is from 1440px
  }

  const breakpoints = getBreakpoints()

  function getBreakpoints () {
    const bxBreakpoints = (window as any).BX?.KTeam?.Breakpoints
    if (bxBreakpoints) {
      return {
        mobile: bxBreakpoints.mobile || defaultBreakpoints.mobile,
        tablet: bxBreakpoints.tablet || defaultBreakpoints.tablet,
        desktop: bxBreakpoints.desktop || defaultBreakpoints.desktop
      }
    }

    return defaultBreakpoints
  }

  function isMobile (): boolean {
    const { width } = useWindowSize()

    return width < breakpoints.mobile
  }

  function isTablet (): boolean {
    const { width } = useWindowSize()

    return width >= breakpoints.mobile && width < breakpoints.tablet
  }

  function isSmallDesktop (): boolean {
    const { width } = useWindowSize()

    return width >= breakpoints.tablet && width < breakpoints.desktop
  }

  function isLargeDesktop (): boolean {
    const { width } = useWindowSize()

    return width >= breakpoints.desktop
  }

  return {
    breakpoints
  }
}
