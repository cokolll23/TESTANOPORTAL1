export function useLoginTemplate (link: string, template?: string) {
  if (template == null) {
    return link
  }

  return template.replace('#login#', link)
}
