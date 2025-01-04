<script setup lang="ts">
import { formatCurrency } from '@core/utils/formatters'
import { postApi } from '@/api/post.api'
import { FURNITURE_TYPES, HTTP_STATUS } from '@/constants/common'
import { isUserLoggedIn } from '@/router/utils'
import { useSnackbar } from '@core/components/Snackbar/useSnackbar'

const route = useRoute()
const id = route.params.id as string

const post = reactive({
  title: '',
  description: '',

  city: null,
  district: null,
  ward: null,
  detail_address: '',
  lat: '',
  lon: '',

  room_type: null,
  acreage: '',
  furniture: '',
  furniture_detail: '',
  room_number: 1,

  rent_fee: '',
  electricity_fee: '',
  water_fee: '',
  internet_fee: '',
  extra_fee: '',

  images: [],

  contact_name: '',
  contact_phone: '',
  contact_email: '',
})

const { successNotify, errorNotify } = useSnackbar()

const getPostById = async () => {
  const result = await postApi.getById(id)
  if (result.status === HTTP_STATUS.OK) {
    result.data.furniture = FURNITURE_TYPES.find(item => item.value === result.data.furniture)?.title
    Object.assign(post, result.data)
    post.images = JSON.parse(result.data.images as string)
  }
}

const saveFavorite = async () => {
  if (!isUserLoggedIn()) {
    errorNotify('Vui lòng đăng nhập để lưu tin')
  }
  else {
    const result = await postApi.saveFavorite(id)
    if (result.status === HTTP_STATUS.OK)
      successNotify('Lưu tin thành công')
    else
      errorNotify('Lưu tin thất bại')
  }
}

getPostById()
</script>

<template>
  <VRow>
    <VCol md="8">
      <VCard class="mb-4">
        <VCardText>
          <VCarousel>
            <VCarouselItem v-for="(image, index) in post.images" :key="index" :src="image" contain />
          </VCarousel>
        </VCardText>
      </VCard>

      <VCard class="mb-4">
        <VCardText>
          <p class="post-title">{{ post.title }}</p>
          <p class="text-sm">
            <VIcon icon="tabler-map" />
            {{ post.detail_address }}
          </p>

          <div class="d-flex pb-4">
            <div class="post-price post-item">
              <VIcon icon="tabler-coin" />
              {{ formatCurrency(post.rent_fee) }}/tháng
            </div>

            <div v-if="post.acreage" class="post-item">
              <VIcon icon="tabler-ruler-measure-2" />
              <span> {{ post.acreage }}/m²</span>
            </div>
            <div class="post-item align-right">
              Mã tin: #{{ post.id }}
            </div>
          </div>
          <VDivider />
          <div class="py-2 font-weight-bold text-black">Thông tin mô tả</div>
          <div style="white-space: break-spaces;">{{ post.description }}</div>

          <VDivider class="my-2" />
          <div class="font-weight-bold text-black">Đặc điểm bất động sản</div>
          <div class="room-detail-container">
            <div class="room-detail-items">
              <span><VIcon icon="tabler-ruler" />Diện tích</span>
              <span> {{ post.acreage }} m²</span>
            </div>
            <div class="room-detail-items">
              <span><VIcon icon="tabler-coin" />Giá thuê</span>
              <span> {{ formatCurrency(post.rent_fee) }}/tháng</span>
            </div>
            <div class="room-detail-items">
              <span><VIcon icon="mdi-sofa" />Nội thất</span>
              <span> {{ post.furniture }}</span>
            </div>

            <div class="room-detail-items">
              <span><VIcon icon="tabler-ruler-measure-2" />Tiền điện</span>
              <span> {{ formatCurrency(post.electricity_fee) }}/số</span>
            </div>

            <div class="room-detail-items">
              <span><VIcon icon="tabler-ruler-measure-2" />Số lượng phòng</span>
              <span> {{ post.room_number }}</span>
            </div>

            <div class="room-detail-items">
              <span><VIcon icon="mdi-faucet" />Tiền nước</span>
              <span> {{ formatCurrency(post.water_fee) }}/m³</span>
            </div>

            <div class="room-detail-items">
              <span><VIcon icon="mdi-web" />Tiền internet</span>
              <span> {{ formatCurrency(post.internet_fee) }}/tháng</span>
            </div>

            <div class="room-detail-items">
              <span><VIcon icon="mdi-account-wrench" />Phí dịch vụ</span>
              <span> {{ post.extra_fee ? `${formatCurrency(post.extra_fee)}/tháng` : 'Không có' }}</span>
            </div>
          </div>

          <VDivider class="my-2" />
          <div class="font-weight-bold text-black">Vị trí trên map</div>
          <iframe
            src="https://maps.google.com/maps?q=10.784175535274935,106.62278652191162&hl=vi&z=21&amp;output=embed"
            width="600" height="450" style="border:0; width: 100%;"
            allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
          />

          <VDivider class="my-2" />
          <div class="font-weight-bold text-black">Vị trí trên map</div>
        </VCardText>
      </VCard>
    </VCol>

    <VCol md="4" cols="12">
      <!-- 👉 User info -->
      <VCard class="mb-4">
        <VCardText>
          <img src="@/assets/default-user.svg" alt="avatar" class="user-avt mb-2">
          <p class="text-center font-weight-bold">{{ post.contact_name }}</p>

          <a :href="`tel:${post.contact_phone}`">
            <VBtn block color="success" class="my-2" prepend-icon="tabler-phone-done">
              {{ post.contact_phone }}
            </VBtn>
          </a>
          <a :href="`https://zalo.me/${post.contact_phone}`" target="_blank" rel="noopener noreferrer">
            <VBtn block color="primary" class="my-2" prepend-icon="tabler-message">
              {{ post.contact_phone }}
            </VBtn>
          </a>

          <div class="d-flex mt-4 gap-2">
            <VBtn class="flex-1-1 text-center" variant="tonal" prepend-icon="tabler-heart" @click="saveFavorite">
              Lưu tin
            </VBtn>
            <VBtn class="flex-1-1 text-center" variant="tonal" prepend-icon="tabler-share">
              Chia sẻ
            </VBtn>
            <VBtn class="flex-1-1 text-center" variant="tonal" prepend-icon="tabler-alert-square">
              Báo cáo
            </VBtn>
          </div>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss" scoped>
.post-title {
  color: #055699;
  font-size: 1.25em;
  font-weight: 600;
}

.post-price {
  color: #068906;
  font-size: 1.25em;
}

.post-item {
  flex: 1;
  font-weight: 600;
}

.align-right {
  text-align: right;
}

.user-avt {
  max-width: 100%;
  max-height: 100%;
  width: 100px;
  border: 1px solid #ddd;
  border-radius: 50%;
  object-fit: cover;
  display: block;
  margin: 0 auto;
  padding: .25rem;
}
a {
  text-decoration: none;
  color: white;
}

.room-detail-container {
  padding: 1em 0;
  display: flex;
  flex-wrap: wrap;
}

.room-detail-items {
  display: flex;
  padding: 1em .5em;
  border-bottom: 1px solid #F2F2F2;
  flex: 1;
  min-width: 50%;
  color: #2C2C2C;

  span:first-child {
    width: 50%;
  }
}
</style>

<route lang="yaml">
name: post-detail
</route>
