import { CapacitorHttp, HttpHeaders, HttpOptions, HttpResponse } from '@capacitor/core';
import { Podcast } from '../types';

let csrfToken: string

export default class Api {

  endpoint: string

  headers: HttpHeaders = {
    'Accept': 'application/json'
  }

  constructor() {
    console.log('instantiate')
    this.endpoint = `${import.meta.env.VITE_LARAVEL_URL}/api/v1/`

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

  req(endpoint: string, data?: object, method?: "get"|"post"): Promise<HttpResponse> {

    const options: HttpOptions = {
      url: `${this.endpoint}${endpoint}`,
      data,
      headers: this.headers
    }

    console.log(options)

    if (method === 'post') {
        return CapacitorHttp.post(options).then(resp => resp)
    }

    return CapacitorHttp.get(options).then(resp => resp)
  }

  async searchPodcasts(term: string): Promise<{results: Podcast[], count: number}> {
    const response = await this.req('podcasts/search', {q: term})
    if (!response.data.results) {
      throw new Error('Unable to search for podcasts')
    }
    return response.data
  }
}
