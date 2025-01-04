<script setup>
import { formatCurrency } from '@core/utils/formatters'
import { postApi } from '@/api/post.api'

const favorites = ref([])

const getAllFavorite = async () => {
  const result = await postApi.getFavorites()

  favorites.value = result.data.items ?? []
}

const removeFavorite = async id => {
  await postApi.removeFavorite(id)

  await getAllFavorite()
}

getAllFavorite()
</script>

<template>
  <div>
    <h1 class="text-h4">Danh sách yêu thích</h1>
    <VDivider class="mb-4" />
    <VRow>
      <VCol v-for="(post, index) in favorites" :key="index" cols="6" :xs="12">
        <VCard>
          <div>
            <RouterLink :to="{ name: 'post-detail', params: { id: post.id } }">
              <div class="d-flex">
                <img :src="JSON.parse(post.images)[0] ?? ''" alt="n">
                <div class="px-6 py-6">
                  <p class="line-clamp-2">{{ post.title }}</p>
                  <p><span style="color: #068906;">{{ formatCurrency(post.rent_fee) }}/tháng</span> {{ post.acreage }} m²</p>
                  <p class="text-sm">
                    <VIcon icon="tabler-map" />
                    {{ post.detail_address }}
                  </p>

                  <p class="line-clamp-2">
                    {{ post.description }}
                  </p>
                </div>
              </div>
            </RouterLink>
            <VDivider />
            <VCardText>
              <div style="display: flex; align-items: center;">
                <img src="@/assets/default-user.svg" alt="avatar" class="user-avt mb-2">
                <span class="text-center font-weight-bold">{{ post.contact_name }}</span>
              </div>

              <div style="display: flex; align-items: center; gap: 4px;">
                <a :href="`tel:${post.contact_phone}`" class="flex-1-1">
                  <VBtn block color="success" class="my-2" prepend-icon="tabler-phone-done">
                    {{ post.contact_phone }}
                  </VBtn>
                </a>

                <VIcon class="text-center" variant="tonal" icon="tabler-heart" @click="removeFavorite(post.id)" />
              </div>
            </VCardText>
          </div>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>

<style lang="scss" scoped>
img {
  width: 50%;
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

.user-avt {
  width: 40px;
  height: 40px;
  margin-right: 6px;
}
</style>
