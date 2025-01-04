import type { AxiosResponse } from 'axios'
import BaseCurlApi from '@/api/base.api'
import request from '@axios'

class PostApi extends BaseCurlApi {
  constructor() {
    super('posts')
  }

  saveFavorite(id: number | string): Promise<AxiosResponse> {
    return request.get(`${this.baseUrl}/save-favorite/${id}`)
  }

  getFavorites(): Promise<AxiosResponse> {
    return request.get(`${this.baseUrl}/favorites/all`)
  }

  async removeFavorite(id) {
    return request.delete(`${this.baseUrl}/delete-favorite/${id}`)
  }
}

export const postApi = new PostApi()
