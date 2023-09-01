import { Podcast } from "../types"
import Api from "./Api"
import Prefs from "./Prefs"

const prefs = new Prefs,
      api = new Api

export const getSubscribedPodcasts = async (): Promise<Podcast[]|null> => {
  return await prefs.get('podcasts')
}

export const getTrendingPodcasts = async (term: string ): Promise<{feeds: Podcast[], count: number}|null> => {
  return await api.searchPodcasts(term)
}

export const getSearchPodcasts = async (): Promise<Podcast[]|null> => {
  return await prefs.get('podcasts')
}
