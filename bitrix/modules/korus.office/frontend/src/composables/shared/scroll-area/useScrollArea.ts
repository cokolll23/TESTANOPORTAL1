export function useScrollArea () {
  const verticalBarStyle = {
    right: '2px'
  }

  const verticalThumbStyle = {
    right: 'var(--kt-ui-scrollbar-rail-offset)'
  }

  const horizontalBarStyle = {
    right: '2px'
  }

  const horizontalThumbStyle = {
    right: 'var(--kt-ui-scrollbar-rail-offset)'
  }

  return {
    verticalBarStyle,
    verticalThumbStyle,

    horizontalBarStyle,
    horizontalThumbStyle
  }
}
