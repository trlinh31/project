import type { AxiosResponse } from 'axios'
import BaseCurlApi from '@/api/base.api'
import request from '@axios'

class CommonApi extends BaseCurlApi {
  constructor() {
    super('')
  }

  getCities(params = {}): Promise<AxiosResponse> {
    return request.get(`${this.baseUrl}cities`, { params })
  }

  getDistricts(params = {}): Promise<AxiosResponse> {
    return request.get(`${this.baseUrl}districts`, { params })
  }

  getWards(params = {}): Promise<AxiosResponse> {
    return request.get(`${this.baseUrl}wards`, { params })
  }
}

const commonApi = new CommonApi()

export const getCities = commonApi.getCities.bind(commonApi)
export const getDistricts = commonApi.getDistricts.bind(commonApi)
export const getWards = commonApi.getWards.bind(commonApi)
