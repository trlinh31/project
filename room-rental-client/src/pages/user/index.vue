<script lang="ts" setup>
import { USER_STATUSES } from '@/constants/common'
import { getUsers } from '@/api/auth'

const users = ref([])
const { t } = useI18n()
const router = useRouter()

const formData = reactive({
  page: 1,
  limit: 10,
  is_active: '',
  name: '',
})

const headers = [
  { title: 'Id', align: 'start', key: 'id' },
  { title: 'Họ và tên', align: 'center', key: 'name' },
  { title: 'Số điện thoại', align: 'center', key: 'phone' },
  { title: 'Email', align: 'center', key: 'email' },
  { title: 'Trạng thái', align: 'center', key: 'is_active' },
  { title: 'Role', align: 'center', key: 'role' },
  { title: t('common.action'), align: 'center', key: 'action' },
]

const getList = async () => {
  const result = await getUsers({ ...formData })

  users.value = result.data.data ?? []
}

const onLimitChange = (limit: number) => {
  formData.limit = limit
  getList()
}

getList()
</script>

<template>
  <VCard title="Danh sách user">
    <template #append>
      <VBtn prepend-icon="tabler-plus" :to="{ name: 'user-add' }">Thêm mới</VBtn>
    </template>
    <VDivider />

    <VCardText>
      <VRow class="mb-3">
        <VCol :md="4" :sm="12">
          <VTextField
            v-model="formData.name"
            label="Họ và tên"
            placeholder=""
          />
        </VCol>
        <VCol :md="4" :sm="12">
          <VSelect
            v-model="formData.is_active"
            density="compact"
            variant="outlined"
            label="Trạng thái"
            :items="USER_STATUSES"
            clearable
          />
        </VCol>

        <VCol :md="2" :sm="12">
          <VBtn prepend-icon="tabler-search" @click="getList">Search</VBtn>
        </VCol>
      </VRow>

      <VDataTable :headers="headers" :items="users" @update:items-per-page="onLimitChange">
        <template #item.is_active="{ item }">
          <VChip
            class="me-3"
            :color="item.is_active ? 'success' : 'error'"
            size="small"
          >
            {{ item.is_active ? 'Active' : 'Inactive' }}
          </VChip>
        </template>

        <template #item.action="{ item }">
          <VBtn
            color="primary" size="x-small" prepend-icon="tabler-pencil"
            @click="router.push({ name: 'user-edit', params: { id: item.id } })"
          >
            {{ t('btn.edit') }}
          </VBtn>

          <VDialog max-width="500">
            <template #activator="{ props: activatorProps }">
              <VBtn
                color="error" size="x-small" prepend-icon="tabler-trash" class="ms-2"
                variant="tonal"
                v-bind="activatorProps"
              >
                {{ t('btn.delete') }}
              </VBtn>
            </template>

            <template #default="{ isActive }">
              <VCard title="Confirm">
                <VCardText>
                  Bạn có muốn xóa bài đăng này không?
                </VCardText>

                <VCardActions>
                  <VSpacer />
                  <VBtn
                    color="error"
                    variant="tonal"
                    @click="() => {
                      postApi.delete(item.id)
                      getList()
                      isActive.value = false
                    }"
                  >
                    {{ $t('btn.delete') }}
                  </VBtn>
                  <VBtn @click="isActive.value = false">{{ $t('btn.cancel') }}</VBtn>
                </VCardActions>
              </VCard>
            </template>
          </VDialog>
        </template>
      </VDataTable>
    </VCardText>
  </VCard>
</template>

