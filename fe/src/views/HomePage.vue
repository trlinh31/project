<template>
  <div>
    <div class="relative" style="height: 400px">
      <div
        class="absolute top-0 left-0 w-full h-full bg-black opacity-50"
      ></div>
      <div
        class="absolute transform -translate-y-1/2"
        style="top: 50%; left: 150px"
      >
        <div
          class="text-5xl font-black text-white uppercase leading-normal mb-4"
        >
          Tìm nhanh, kiếm dễ <br />
          Trọ Mới toàn quốc
        </div>
        <p class="text-white w-1/2">
          Trang thông tin và cho thuê phòng trọ nhanh chóng, hiệu quả với hơn
          500 tin đăng mới và 30.000 lượt xem mỗi ngày
        </p>
      </div>
      <img
        src="https://tromoi.com/frontend/home/images/banner.jpg"
        class="w-full h-full object-cover"
        alt=""
      />
    </div>
    <div class="container py-6">
      <vx-input-group class="mb-4">
        <vs-input
          icon-pack="feather"
          icon="icon-search"
          class="inputx text-xl"
          v-model="filter.detail_address"
          placeholder="Nhập tối đa 5 địa điểm, dự án. Ví dụ: Quận Hoàn Kiếm, Quận Đống Đa"
        />

        <template slot="append">
          <div class="append-text btn-addon">
            <vs-button color="primary">Tìm kiếm</vs-button>
          </div>
        </template>
      </vx-input-group>
      <div class="flex gap-x-3">
        <div class="md:w-1/4 w-full">
          <v-select
            :options="cities"
            v-model="filter.city"
            placeholder="Tỉnh/Thành phố"
          />
        </div>
        <div class="md:w-1/4 w-full">
          <v-select
            :options="roomTypes"
            v-model="filter.room_type"
            placeholder="Loại phòng"
          />
        </div>
        <div class="md:w-1/4 w-full">
          <v-select
            :options="priceRange"
            v-model="filter.price"
            placeholder="Mức giá"
          />
        </div>
        <div class="md:w-1/4 w-full">
          <v-select
            :options="areaRange"
            v-model="filter.acreage"
            placeholder="Diện tích"
          />
        </div>
      </div>

      <div class="mt-10">
        <vs-breadcrumb :items="breadcrumbItems"></vs-breadcrumb>
      </div>

      <vx-card
        :title="'Cho Thuê Phòng Trọ Hà Nội, Nhà Trọ Hà Nội T1/2025'"
        class="my-6"
      >
        <div class="vx-row">
          <div
            class="vx-col md:w-1/3 w-full mb-base"
            v-for="item in posts"
            :key="item.id"
          >
            <vx-card @click="navigateToRoomDetail(item.id)">
              <img
                :src="item.images"
                alt="content-img"
                height="500"
                class="w-full object-cover rounded-lg"
              />
              <div class="mt-6 mb-3">
                <h5 class="mb-2 truncate">{{ item.title }}</h5>
                <p class="text-grey mb-1">Liên hệ: {{ item.contact_phone }}</p>
                <p class="text-grey truncate">
                  Địa chỉ: {{ item.detail_address }}
                </p>
              </div>
              <vs-divider></vs-divider>
              <div class="flex justify-between flex-wrap">
                <span>
                  <p class="text-grey">Giá</p>
                  <p class="text-lg">
                    {{ formatPriceVND(item.rent_fee) }}/tháng
                  </p>
                </span>
                <span>
                  <p class="text-grey">Ngày đăng</p>
                  <p class="text-lg">{{ convertDate(item.created_at) }}</p>
                </span>
              </div>
            </vx-card>
          </div>
        </div>
      </vx-card>
    </div>
  </div>
</template>
<script>
import vSelect from "vue-select";
import postService from "../services/post.service";

export default {
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      posts: [],
      cities: [],
      filter: {
        city: null,
        detail_address: "",
        room_type: null,
        price: null,
        acreage: null,
      },
      roomTypes: [
        {
          label: "Phòng trọ",
          value: "MOTEL",
        },
        {
          label: "Chung cư",
          value: "APARTMENT",
        },
        {
          label: "Nhà nguyên căn",
          value: "HOUSE",
        },
        {
          label: "Văn phòng",
          value: "OFFICE",
        },
      ],
      priceRange: [
        {
          label: "Dưới 1 triệu",
          value: "1",
        },
        {
          label: "1 triệu - 2 triệu",
          value: "2",
        },
        {
          label: "2 triệu - 3 triệu",
          value: "3",
        },
        {
          label: "Trên 3 triệu",
          value: "4",
        },
      ],
      areaRange: [
        {
          label: "Dưới 20 m2",
          value: "1",
        },
        {
          label: "20 m2 - 30 m2",
          value: "2",
        },
        {
          label: "30 m2 - 40 m2",
          value: "3",
        },
        {
          label: "Trên 40 m2",
          value: "4",
        },
      ],
      breadcrumbItems: [
        {
          title: "Trang chủ",
          url: "/",
        },
        {
          title: "Hà Nội",
        },
        {
          title: "Nhà trọ, phòng trọ tại Hà Nội",
          active: true,
        },
      ],
    };
  },
  methods: {
    fetchCities() {
      this.$vs.loading();
      postService
        .getCities()
        .then((response) => {
          const { items } = response.data;
          this.cities = items.map((item) => ({
            label: item.name,
            value: item.id,
          }));
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => this.$vs.loading.close());
    },
    formatPriceVND(value) {
      return new Intl.NumberFormat("vi-VN").format(value) + "đ";
    },
    convertDate(date) {
      const d = new Date(date);
      const day = String(d.getDate()).padStart(2, "0");
      const month = String(d.getMonth() + 1).padStart(2, "0");
      const year = d.getFullYear();
      return `${day}/${month}/${year}`;
    },
    fetchPosts() {
      this.$vs.loading();
      postService
        .getPosts()
        .then((response) => {
          const { items } = response.data;
          this.posts = items;
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.$vs.loading.close();
        });
    },
    navigateToRoomDetail(id) {
      this.$router.push(`/room/${id}`);
    },
  },
  mounted() {
    this.fetchCities();
    this.fetchPosts();
  },
};
</script>
<style>
.truncate {
  text-overflow: ellipsis;
  overflow: hidden;
  white-space: nowrap;
}
</style>
