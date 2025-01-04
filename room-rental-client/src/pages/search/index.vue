<script setup lang="ts">
import { HTTP_STATUS, ROOM_TYPES } from '@/constants/common'
import { getCities, getDistricts, getWards } from '@/api/common'
import { formatCurrency } from '@core/utils/formatters'
import { postApi } from '@/api/post.api'

const cities = ref([])
const districts = ref([])
const wards = ref([])

const post = reactive({
  city: null,
  district: null,
  ward: null,
  lat: '',
  lon: '',
  limit: 10,

  room_type: null,
  price: [0, 10000000],
})

const getCitiesList = async () => {
  const res = await getCities()

  cities.value = res.data.items
}

const getDistrictList = async () => {
  const res = await getDistricts({ city_id: post.city })

  districts.value = res.data.items
}

const getWardList = async () => {
  const res = await getWards({ district_id: post.district })

  wards.value = res.data.items
}

const changeCity = async () => {
  post.district = null
  post.ward = null

  await getDistrictList()
}

const changeDistrict = async () => {
  post.ward = null
  await getWardList()
}

const posts = ref([])

const getListPost = async () => {
  const result = await postApi.get({ ...post })
  if (result.status === HTTP_STATUS.OK)
    posts.value = result.data.data ?? []
}

getListPost()
getCitiesList()
</script>

<template>
  <div>
    <VCard class="mb-4" title="Tìm kiếm">
      <VCardText>
        <VRow class="mb-4">
          <VCol md="3">
            <VSelect
              v-model="post.city"
              placeholder="Chọn Tỉnh/Thành phố"
              label="Tỉnh/Thành phố"
              :items="cities"
              item-value="id"
              item-title="name"
              class="mb-4"
              filterable
              filter-key="name"
              @update:model-value="changeCity"
            />
          </VCol>
          <VCol md="3">
            <VSelect
              v-model="post.district"
              :disabled="!post.city"
              placeholder="Chọn Quận/Huyện"
              label="Quận/Huyện"
              :items="districts"
              class="mb-4"
              item-value="id"
              item-title="name"
              filter-key="name"
              @update:model-value="changeDistrict"
            />
          </VCol>
          <VCol md="3">
            <VSelect
              v-model="post.ward"
              :disabled="!post.district"
              placeholder="Chọn Phường/Xã"
              label="Phường/Xã"
              :items="wards"
              class="mb-4"
              item-value="id"
              item-title="name"
              filter-key="name"
            />
          </VCol>
          <VCol md="3">
            <VSelect
              v-model="post.room_type"
              label="Loại nhà đất"
              :items="ROOM_TYPES"
            />
          </VCol>

          <VCol md="6">
            <div class="d-flex" style="justify-content: center; align-items: center;">
              <VRangeSlider
                v-model="post.price"
                label="Giá"
                step="100000"
                :max="10000000"
                :min="0"
                class="pa-2"
              />

              <span>{{ formatCurrency(post.price[0]) }}</span> ~
              <span>{{ formatCurrency(post.price[1]) }}</span>
            </div>
          </VCol>
        </VRow>

        <div style="text-align: right;">
          <VBtn prepend-icon="tabler-search" @click="getListPost">Tìm kiếm</VBtn>
        </div>
      </VCardText>
    </VCard>

    <VRow v-if="posts.length">
      <VCol v-for="(cur, index) in posts" :key="index" cols="3" :xs="6">
        <VCard>
          <RouterLink :to="{ name: 'post-detail', params: { id: cur.id } }">
            <VCardText>
              <img :src="JSON.parse(cur.images)[0] ?? ''" alt="n">
              <p class="line-clamp-2">{{ cur.title }}</p>
              <p><span style="color: #068906;">{{ formatCurrency(cur.rent_fee) }}/tháng</span> {{ cur.acreage }} m²</p>
            </VCardText>
          </RouterLink>
        </VCard>
      </VCol>
    </VRow>

    <VRow v-else>
      <VCol>
        <VCard>
          <VCardText>
            Không tìm thấy kết quả nào
          </VCardText>
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

