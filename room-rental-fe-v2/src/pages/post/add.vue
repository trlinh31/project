<script setup lang="ts">
import { AxiosError } from 'axios'
import { integerValidator, phoneNumberValidator, requiredValidator } from '@validators'
import type { PostStatus } from '@/constants/common'
import { FURNITURE_TYPES, POST_STATUSES, ROOM_TYPES } from '@/constants/common'

import { useUserStore as userStore } from '@/pinia/userStore'
import { getCities, getDistricts, getWards } from '@/api/common'
import { postApi } from '@/api/post.api'
import { useSnackbar } from '@core/components/Snackbar/useSnackbar'

const userInfo = userStore.userInfo
const { successNotify, errorNotify } = useSnackbar()
const { t } = useI18n()

const cities = ref([])
const districts = ref([])
const wards = ref([])

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

  contact_name: userInfo.name,
  contact_phone: userInfo.phone,
  contact_email: userInfo.email,
})

const imageRef = ref()

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

const router = useRouter()

const savePost = async (status: PostStatus) => {
  try {
    const images = [await imageRef.value.upload('avt')]

    await postApi.save({ ...post, status, images, lat: '1', lon: '2' })
    successNotify('Thêm mới bài đăng thành công')
    await router.push({ name: 'post' })
  }
  catch (e) {
    if (e instanceof AxiosError)
      errorNotify(e.message || e.response?.data?.message)
  }
}

getCitiesList()
</script>

<template>
  <div>
    <div class="d-flex flex-wrap justify-start justify-sm-space-between gap-y-4 gap-x-6 mb-4">
      <div class="d-flex flex-column justify-center">
        <h4 class="text-h4 font-weight-medium">
          Đăng tin
        </h4>
      </div>

      <div class="d-flex gap-4 align-center flex-wrap">
        <VBtn variant="tonal" color="secondary" @click="router.push({ name: 'post' })">Hủy</VBtn>
        <VBtn variant="tonal" color="primary" @click="savePost(POST_STATUSES.DRAFT)">Lưu nháp</VBtn>
        <VBtn @click="savePost(POST_STATUSES.PENDING)">Đăng bài</VBtn>
      </div>
    </div>

    <VRow>
      <VCol md="8">
        <VCard class="mb-4" title="Thông tin chung">
          <VCardText>
            <ImageUpload ref="imageRef" class="mb-4" />

            <AppTextField
              v-model="post.title"
              label="Tiêu đề"
              :rules="[requiredValidator]"
              placeholder="Tiêu đề đăng tin"
              class="mb-4"
            />
            <AppTextarea
              v-model="post.description"
              :rules="[requiredValidator]"
              placeholder="Mô tả chi tiết về:
                  • Loại hình bất động sản
                  • Vị trí
                  • Diện tích, tiện ích
                  • Tình trạng nội thất
                  ...
                  (VD: Khu nhà có vị trí thuận lợi, gần công viên, trường học...)
                  "
              label="Mô tả chi tiết"
              rows="8"
            />
          </VCardText>
        </VCard>

        <VCard class="mb-4" title="Địa chỉ">
          <VCardText>
            <AppSelect
              v-model="post.city"
              placeholder="Chọn Tỉnh/Thành phố"
              label="Tỉnh/Thành phố"
              :rules="[requiredValidator]"
              :items="cities"
              item-value="id"
              item-title="name"
              class="mb-4"
              filterable
              filter-key="name"
              @change="changeCity"
            />

            <AppSelect
              v-model="post.district"
              :disabled="!post.city"
              placeholder="Chọn Quận/Huyện"
              label="Quận/Huyện"
              :rules="[requiredValidator]"
              :items="districts"
              class="mb-4"
              item-value="id"
              item-title="name"
              filter-key="name"
              @change="changeDistrict"
            />

            <AppSelect
              v-model="post.ward"
              :disabled="!post.district"
              placeholder="Chọn Phường/Xã"
              label="Phường/Xã"
              :rules="[requiredValidator]"
              :items="wards"
              class="mb-4"
              item-value="id"
              item-title="name"
              filter-key="name"
            />

            <AppTextarea
              v-model="post.detail_address"
              :rules="[requiredValidator]"
              label="Địa chỉ chi tiết"
              placeholder="Số nhà, tên đường, khu vực..."
              class="mb-4"
            />
          </VCardText>
        </VCard>

        <VCard title="Thông tin liên hệ" class="mb-4">
          <VCardText>
            <AppTextField
              v-model="post.contact_name"
              :rules="[requiredValidator]"
              label="Tên liên hệ"
              placeholder="Nhập tên liên hệ"
              class="mb-4"
            />

            <AppTextField
              v-model="post.contact_email"
              label="Email liên hệ (không bắt buộc)"
              placeholder="Email liên hệ"
              class="mb-4"
            />

            <AppTextField
              v-model="post.contact_phone"
              :rules="[requiredValidator, phoneNumberValidator]"
              label="Số điện thoại"
              placeholder="Số điện thoại liên hệ"
              class="mb-4"
            />
          </VCardText>
        </VCard>
      </VCol>

      <VCol md="4" cols="12">
        <!-- 👉 Price -->
        <VCard title="Thiết lập giá" class="mb-4">
          <VCardText>
            <AppTextField
              v-model="post.rent_fee" label="Giá thuê"
              placeholder="20"
              class="mb-4"
              :rules="[requiredValidator, integerValidator]"
              prepend-inner-icon="mdi-currency-usd"
            />

            <AppTextField
              v-model="post.electricity_fee"
              label="Giá điện"
              placeholder="5k/số"
              class="mb-4"
              prepend-inner-icon="mdi-lightning-bolt"
              suffix="/số"
              :rules="[integerValidator]"
            />

            <AppTextField
              v-model="post.water_fee"
              label="Giá nước"
              prepend-inner-icon="mdi-faucet"
              suffix="/m³"
              :rules="[integerValidator]"
              class="mb-4"
            />

            <AppTextField
              v-model="post.internet_fee"
              label="Cước internet"
              prepend-inner-icon="mdi-web"
              :rules="[integerValidator]"
              class="mb-4"
            />

            <AppTextField
              v-model="post.extra_fee"
              label="Phí dịch vụ"
              prepend-inner-icon="mdi-account-wrench"
              :rules="[integerValidator]"
              class="mb-4"
            />
          </VCardText>
        </VCard>

        <!-- 👉 Detail info -->
        <VCard title="Thông tin chi tiết">
          <VCardText>
            <AppSelect
              v-model="post.room_type"
              placeholder="Loại phòng"
              label="Loại phòng"
              :rules="[requiredValidator]"
              :items="ROOM_TYPES"
              prepend-inner-icon="mdi-home-city"
              class="mb-4"
            />

            <AppSelect
              v-model="post.furniture"
              placeholder="Nội thất"
              label="Nội thất"
              :rules="[requiredValidator]"
              :items="FURNITURE_TYPES"
              prepend-inner-icon="mdi-sofa"
              class="mb-4"
            />

            <AppTextarea
              v-if="post.furniture && post.furniture !== FURNITURE_TYPES[0].value"
              v-model="post.furniture_detail"
              placeholder="Nội thất chi tiết (VD: Tivi, tủ lạnh, máy giặt...)"
              label="Nội thất chi tiết"
              class="mb-4"
            />

            <AppTextField
              v-model="post.acreage"
              label="Diện tích"
              placeholder="20"
              class="mb-4"
              suffix="m²"
              :rules="[requiredValidator, integerValidator]"
              prepend-inner-icon="tabler-ruler-measure-2"
            />

            <AppTextField
              v-model="post.room_number"
              label="Số lượng phòng"
              placeholder="1"
              type="number"
            />
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>

<style lang="scss" scoped>
.drop-zone {
  border: 2px dashed rgba(var(--v-theme-on-surface), 0.12);
  border-radius: 6px;
}
</style>

<style lang="scss">
.inventory-card {
  .v-tabs.v-tabs-pill {
    .v-slide-group-item--active.v-tab--selected.text-primary {
      h6 {
        color: #fff !important;
      }
    }
  }

  .v-radio-group,
  .v-checkbox {
    .v-selection-control {
      align-items: start !important;
    }

    .v-label.custom-input {
      border: none !important;
    }
  }
}
</style>
