<template>
  <div class="container mt-10">
    <div class="vx-row mb-base">
      <div class="vx-col md:w-2/3 w-full">
        <div class="flex justify-center bg-white shadow">
          <img :src="room.images" class="object-contain" height="500" alt="" />
        </div>
      </div>
      <div class="vx-col md:w-1/3 w-full">
        <div class="bg-white shadow flex flex-col">
          <div class="p-4">
            <img
              src="@/assets/default-user.svg"
              alt="avatar"
              class="user-avt mb-2"
            />
            <p class="text-center font-bold mb-6">{{ room.contact_name }}</p>
            <vs-button
              color="success"
              icon-pack="feather"
              icon="icon-phone"
              class="w-full mb-3 font-bold"
              >{{ room.contact_phone }}</vs-button
            >
            <vs-button
              class="w-full font-bold mb-3"
              icon-pack="feather"
              icon="icon-message-circle"
              >{{ room.contact_phone }}</vs-button
            >
            <div class="flex gap-2">
              <vs-button
                color="primary"
                class="flex-1 px-0"
                icon-pack="feather"
                icon="icon-heart"
                type="flat"
                >Lưu tin</vs-button
              >
              <vs-button
                color="primary"
                class="flex-1 px-0"
                icon-pack="feather"
                icon="icon-share-2"
                type="flat"
                >Chia sẻ</vs-button
              >
              <vs-button
                color="primary"
                class="flex-1 px-0"
                icon-pack="feather"
                icon="icon-alert-circle"
                type="flat"
                >Báo cáo</vs-button
              >
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="vx-row">
      <div class="vx-col md:w-2/3 w-full">
        <div class="bg-white shadow p-4">
          <h3 class="text-primary font-bold mb-3">{{ room.title }}</h3>
          <div class="flex items-center mb-6 gap-2">
            <map-icon size="1.5x"></map-icon>
            <div>{{ room.detail_address }}</div>
          </div>
          <div class="flex items-center">
            <div class="text-3xl text-success flex items-center font-bold">
              <dollar-sign-icon></dollar-sign-icon>
              {{ formatPriceVND(room.rent_fee) }}/tháng
            </div>
            <div class="flex text-lg ml-10 gap-2 items-center">
              <crop-icon size="24"></crop-icon>
              {{ room.acreage }} m²
            </div>
          </div>
          <vs-divider />
          <div class="py-2 font-bold text-black">Thông tin mô tả</div>
          <div class="vx-row">
            <div class="vx-col md:w-1/2 w-full">
              <div class="flex items-center gap-2">
                <span>Nội thất: </span>
                <span>{{ room.furniture }}</span>
              </div>
            </div>
            <div class="vx-col md:w-1/2 w-full">
              <div class="flex items-center gap-2">
                <span>Tiền điện: </span>
                <span>{{ formatPriceVND(room.electricity_fee) }}/tháng</span>
              </div>
            </div>
          </div>
          <vs-divider />
          <div class="vx-row">
            <div class="vx-col md:w-1/2 w-full">
              <div class="flex items-center gap-2">
                <span>Số lượng phòng: </span>
                <span>1</span>
              </div>
            </div>
            <div class="vx-col md:w-1/2 w-full">
              <div class="flex items-center gap-2">
                <span>Tiền nước: </span>
                <span>{{ formatPriceVND(room.water_fee) }}/m³</span>
              </div>
            </div>
          </div>
          <vs-divider />
          <div class="vx-row">
            <div class="vx-col md:w-1/2 w-full">
              <div class="flex items-center gap-2">
                <span>Tiền internet: </span>
                <span>{{ formatPriceVND(room.internet_fee) }}/tháng</span>
              </div>
            </div>
            <div class="vx-col md:w-1/2 w-full">
              <div class="flex items-center gap-2">
                <span>Phí dịch vụ: </span>
                <span>{{ formatPriceVND(room.extra_fee) }}/tháng</span>
              </div>
            </div>
          </div>
          <vs-divider />
          <iframe
            src="https://maps.google.com/maps?q=10.784175535274935,106.62278652191162&hl=vi&z=21&amp;output=embed"
            width="600"
            height="450"
            style="border: 0; width: 100%"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
          />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import postService from "../services/post.service";
import { MapIcon, DollarSignIcon, CropIcon } from "vue-feather-icons";

export default {
  components: {
    MapIcon,
    DollarSignIcon,
    CropIcon,
  },
  data() {
    return {
      room: null,
    };
  },
  methods: {
    formatPriceVND(value) {
      return new Intl.NumberFormat("vi-VN").format(value) + "đ";
    },
    fetchPost(id) {
      this.$vs.loading();
      postService
        .getById(id)
        .then((response) => {
          const { data } = response;
          this.room = data;
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.$vs.loading.close();
        });
    },
  },
  mounted() {
    const id = this.$route.params.id;
    this.fetchPost(id);
  },
};
</script>
<style>
.user-avt {
  max-width: 100%;
  max-height: 100%;
  width: 100px;
  border: 1px solid #ddd;
  border-radius: 50%;
  object-fit: cover;
  display: block;
  margin: 0 auto;
  padding: 0.25rem;
}
</style>
