import request from '@axios'

interface LoginRequest {
  email: string
  password: string
}

interface RegisterRequest {
  name: string
  email: string
  password: string
  avt?: string
  is_active?: boolean
}

export const login = (data: LoginRequest) => {
  return request.post('api_admin/login', data)
}

export const logout = () => {
  return request.post('api_admin/logout')
}

export const register = (data: RegisterRequest) => {
  return request.post('api_admin/register', data)
}

export const activeAccount = (token: string) => {
  return request.get(`api_admin/active-user/${token}`)
}

export const profile = () => {
  return request.get('api_admin/profile')
}

export const getUsers = (params: Record<string, any>) => {
  return request.get('api_admin/users', { params })
}

export const getUserById = (id: number | string) => {
  return request.get(`api_admin/users/${id}`)
}

export const updateUser = (id: number | string, data: Record<string, any>) => {
  return request.put(`api_admin/users/${id}`, data)
}
