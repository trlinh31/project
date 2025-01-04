import BaseCurlApi from '@/api/base.api'

class PostApi extends BaseCurlApi {
  constructor() {
    super('posts')
  }
}

export const postApi = new PostApi()
