export function getIconSource (name?: string) {
  if (name == null) {
    return undefined
  }

  if (name.startsWith('img:')) {
    return name
  }

  return `var(--kt-ui-icon-${name})`
}
