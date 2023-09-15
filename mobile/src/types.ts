export interface Podcast {
  id: string
  podcastGuid?: string
  title: string
  description: string
  artwork: string
  categories: {[key: string]: string}
}

export interface Episode {
  guid: string
  title: string
  description: string
  artwork: string
}
