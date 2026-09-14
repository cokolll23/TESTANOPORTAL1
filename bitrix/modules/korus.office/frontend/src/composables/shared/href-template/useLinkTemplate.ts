export function useLinkTemplate (link: string, template?: string) {
  if (template == null) {
    return link
  }

  return template.replace('#LINK#', link)
}
