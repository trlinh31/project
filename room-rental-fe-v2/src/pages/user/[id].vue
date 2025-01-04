<script setup lang="ts">
import { AxiosError } from 'axios'
import { emailValidator, phoneNumberValidator, requiredValidator } from '@validators'
import { HTTP_STATUS, ROLES, USER_STATUSES } from '@/constants/common'

import { useSnackbar } from '@core/components/Snackbar/useSnackbar'
import { getUserById, register, updateUser } from '@/api/auth'

const { successNotify, errorNotify } = useSnackbar()
const router = useRouter()
const route = useRoute()

const user = reactive({
  name: '',
  email: '',
  phone: '',
  address: '',
  is_active: '',
})

const id = route.params.id as string

const getUser = async () => {
  const result = await getUserById(id)

  if (result.status === HTTP_STATUS.OK)
    Object.assign(user, result.data)
}

const savePost = async () => {
  try {
    await updateUser(id, { ...user })

    successNotify('Chỉnh sửa tài khoản thành công')
    await router.push({ name: 'user' })
  }
  catch (e) {
    if (e instanceof AxiosError)
      errorNotify(e.response?.data?.message || e.message)
  }
}

getUser()
</script>

<template>
  <div>
    <div class="d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-4">
      <div class="d-flex flex-column justify-center">
        <h4 class="text-h4 font-weight-medium">
          Thêm mới user
        </h4>
      </div>

      <div class="d-flex gap-4 align-center flex-wrap">
        <VBtn @click="savePost">Lưu</VBtn>
      </div>
    </div>

    <VRow>
      <VCol md="8">
        <VCard class="mb-4" title="Thông tin chung">
          <VCardText>
            <AppTextField
              v-model="user.name"
              label="Họ và tên"
              :rules="[requiredValidator]"
              placeholder="Họ và tên"
              class="mb-4"
            />
            <AppTextField
              v-model="user.email"
              label="Email"
              :rules="[requiredValidator, emailValidator]"
              placeholder="user@gmail.com"
              type="email"
              class="mb-4"
            />

            <AppTextField
              v-model="user.phone"
              label="Số điện thoại"
              :rules="[requiredValidator, phoneNumberValidator]"
              placeholder="0868 68 68 68"
              class="mb-4"
            />

            <AppTextarea
              v-model="user.address"
              :rules="[requiredValidator]"
              label="Địa chỉ"
              class="mb-4"
            />

            <AppSelect
              v-model="user.role"
              placeholder="Role"
              label="Role"
              :rules="[requiredValidator]"
              :items="ROLES"
              prepend-inner-icon="mdi-account-group"
              class="mb-4"
            />

            <AppSelect
              v-model="user.is_active"
              placeholder="Trạng thái"
              label="Trạng thái"
              :items="USER_STATUSES"
              :rules="[requiredValidator]"
              class="mb-4"
            />
          </VCardText>
        </VCard>
      </VCol>

      <VCol md="4" cols="12" />
    </VRow>
  </div>
</template>

<route lang="yaml">
name: user-edit
</route>
