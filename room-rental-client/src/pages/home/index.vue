<script setup lang="ts">
import { formatCurrency } from '@core/utils/formatters'
import { postApi } from '@/api/post.api'
import { HTTP_STATUS } from '@/constants/common'

const posts = ref([])

const getListPost = async () => {
  const result = await postApi.get({ limit: 10 })
  if (result.status === HTTP_STATUS.OK)
    posts.value = result.data.data ?? []
}

getListPost()
</script>

<template>
  <div>
    <VRow class="gap-y-4">
      <VCol :cols="4">
        <img src="@/assets/images/carousel-dang-nhanh-thue-le.jpg" alt="">
      </VCol>
      <VCol :cols="4">
        <img src="@/assets/images/khuyen-mai-50-cho-thanh-vien-moi.jpg" alt="">
      </VCol>
      <VCol :cols="4">
        <img src="@/assets/images/carousel-phongtro-sinh-vien.jpg" alt="">
      </VCol>
    </VRow>

    <p class="text-h5 pt-4">Tin đăng mới cập nhật</p>
    <VRow>
      <VCol v-for="(post, index) in posts" :key="index" cols="3" :xs="6">
        <VCard>
          <RouterLink :to="{ name: 'post-detail', params: { id: post.id } }">
            <VCardText>
              <img :src="JSON.parse(post.images)[0] ?? ''" alt="n">
              <p class="line-clamp-2">{{ post.title }}</p>
              <p><span style="color: #068906;">{{ formatCurrency(post.rent_fee) }}/tháng</span> {{ post.acreage }} m²</p>
            </VCardText>
          </RouterLink>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>

<style lang="scss" scoped>
img {
  width: 100%;
  border-radius: 5px;
  object-fit: contain;
  height: 180px;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  overflow: hidden;
  white-space: normal;
  color: #055699;
  height: 48px;
}
</style>

<route lang="yaml">
meta:
  layout: user
  action: read
  subject: Auth
  redirectIfLoggedIn: true
</route>
