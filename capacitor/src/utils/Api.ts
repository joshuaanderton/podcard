import { CapacitorHttp, HttpHeaders, HttpOptions, HttpResponse } from '@capacitor/core';
import { Podcast } from '../types';

let csrfToken: string

export default class Api {

  endpoint: string

  headers: HttpHeaders = {
    'Accept': 'application/json'
  }

  constructor() {
    this.endpoint = `${import.meta.env.VITE_LARAVEL_URL || 'https://podcard.co'}/api/v1/`

    CapacitorHttp.get({ url: 'sanctum/csrf-cookie' }).then(() => {
      this.headers['X-XSRF-TOKEN'] = this.csrfToken
      console.log(this.headers)
    })
  }

  get csrfToken(): string {
    if (csrfToken) {
      return csrfToken
    }

    let xsrfToken: any

    xsrfToken = document.cookie.match('(^|; )XSRF-TOKEN=([^;]*)')
    xsrfToken = xsrfToken[2]
    xsrfToken = decodeURIComponent(xsrfToken)

    return csrfToken = xsrfToken
  }

  req(endpoint: string, data?: object, method?: "get"|"post"): Promise<any> {

    const options: HttpOptions = {
      url: `${this.endpoint}${endpoint}`,
      data,
      headers: this.headers
    }

    let request

    if (method === 'post') {
      request = CapacitorHttp.post(options)
    } else {
      request = CapacitorHttp.get(options)
    }

    return (
      request
        .then(resp => resp.data)
        .catch(err => {
          console.log(err)
          return null
        })
        .then(value => value)
    )
  }

  async searchPodcasts(term: string): Promise<{feeds: Podcast[], count: number}|null> {
    return await this.req('podcasts/search', {q: term})
  }

  async trendingPodcasts(): Promise<{feeds: Podcast[], count: number}|null> {
    return await this.req('podcasts/trending')
  }
}
