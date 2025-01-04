<script lang="ts" setup>
import { formatCurrency, formatDate } from '@core/utils/formatters'
import { postApi } from '@/api/post.api'
import { POST_STATUS_COLORS, POST_STATUS_TEXTS } from '@/constants/common'

const transactions = ref([])
const { t } = useI18n()
const router = useRouter()

const formData = reactive({
  page: 1,
  limit: 10,
  status: '',
})

const headers = [
  { title: 'Id', align: 'start', key: 'id' },
  { title: 'Title', align: 'center', key: 'title' },
  { title: 'Ảnh', align: 'center', key: 'amount' },
  { title: 'Tiền thuê phòng', align: 'center', key: 'rent_fee', value: (item: Record<string, any>) => formatCurrency(item.rent_fee) },
  { title: 'Trạng thái', align: 'center', key: 'status' },
  { title: 'Ngày đăng', align: 'center', key: 'created_at' },
  { title: t('common.action'), align: 'center', key: 'action' },
]

const getList = async () => {
  const result = await postApi.get({ ...formData })

  transactions.value = result.data.data ?? []
}

const onLimitChange = (limit: number) => {
  formData.limit = limit
  getList()
}

getList()
</script>

<template>
  <VCard title="Danh sách bài đăng">
    <template #append>
      <VBtn prepend-icon="tabler-plus" :to="{ name: 'post-add' }">Đăng ngay</VBtn>
    </template>
    <VDivider />

    <VCardText>
      <VRow class="mb-3">
        <VCol :md="4" :sm="12">
          <VSelect
            v-model="formData.status"
            density="compact"
            variant="outlined"
            label="Trạng thái"
            item-title="text"
            item-value="status"
            :items="POST_STATUS_TEXTS"
            clearable
          />
        </VCol>

        <VCol :md="2" :sm="12">
          <VBtn prepend-icon="tabler-search" @click="getList">Search</VBtn>
        </VCol>
      </VRow>

      <VDataTable :headers="headers" :items="transactions" @update:items-per-page="onLimitChange">
        <template #item.created_at="{ item }">{{ formatDate(item.created_at) }}</template>
        <template #item.status="{ item }">
          <VChip
            class="me-3"
            :color="POST_STATUS_COLORS[item.status]"
            size="small"
          >
            {{ item.status }}
          </VChip>
        </template>

        <template #item.action="{ item }">
          <VBtn
            class="me-3"
            color="primary" size="x-small" prepend-icon="tabler-pencil"
            @click="router.push({ name: 'post-edit', params: { id: item.id } })"
          >
            {{ t('btn.edit') }}
          </VBtn>

          <VBtn
            variant="tonal"
            color="primary" size="x-small" prepend-icon="tabler-badge-ad"
          >
            AD
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

