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
      <form class="mb-6">
        <vx-input-group class="mb-4">
          <vs-input
            icon-pack="feather"
            icon="icon-search"
            class="inputx text-xl"
            v-model="filter.detail_address"
            placeholder="Nhập địa chỉ..."
          />

          <template slot="append">
            <div class="append-text btn-addon">
              <vs-button color="primary" @click.prevent="fetchPosts">
                Tìm kiếm
              </vs-button>
            </div>
          </template>
        </vx-input-group>
        <div class="flex gap-x-3">
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
              v-model="filter.rent_fee"
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
      </form>

      <vx-card>
        <vx-input-group class="mb-6 w-1/6">
          <vs-input
            type="number"
            v-model="filter.radius"
            placeholder="Nhập bán kính..."
          />

          <template slot="append">
            <div class="append-text bg-primary">
              <span>Km</span>
            </div>
          </template>
        </vx-input-group>
        <gmap-map
          :center="center"
          :zoom="15"
          style="width: 100%; height: 500px"
          @click="handleMapClick"
        >
          <gmap-info-window
            :options="infoOptions"
            :position="infoWindowPos"
            :opened="infoWinOpen"
            @closeclick="infoWinOpen = false"
          >
            <div v-if="infoContent">{{ infoContent }}</div>
          </gmap-info-window>

          <gmap-marker
            v-if="userPosition"
            :position="userPosition"
            :icon="userPositionIcon"
            :clickable="true"
            @click="toggleInfoWindow(userPosition, null)"
          ></gmap-marker>

          <gmap-marker
            :key="i"
            v-for="(m, i) in markers"
            :position="m.position"
            :clickable="true"
            @click="toggleInfoWindow(m, i)"
          ></gmap-marker>
        </gmap-map>
      </vx-card>

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
                :src="item.images.length > 0 && item.images[0]"
                alt=""
                height="300"
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

      <vx-card
        :title="'Bài viết yêu thích'"
        class="my-6"
        v-if="favorites.length > 0"
      >
        <div class="vx-row">
          <div
            class="vx-col md:w-1/3 w-full mb-base"
            v-for="item in favorites"
            :key="item.id"
          >
            <vx-card @click="navigateToRoomDetail(item.post_id)">
              <img
                :src="item.image || ''"
                alt=""
                height="300"
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
      favorites: [],
      cities: [],
      filter: {
        city: null,
        detail_address: "",
        room_type: null,
        rent_fee: null,
        acreage: null,
        lat: null,
        lon: null,
        radius: 10,
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
      center: { lat: 0, lng: 0 },
      infoContent: "",
      infoWindowPos: null,
      infoWinOpen: false,
      currentMidx: null,
      infoOptions: {
        pixelOffset: { width: 0, height: -35 },
      },
      markers: [],
      userPosition: null,
      userPositionIcon: {
        url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
      },
    };
  },
  methods: {
    handleMapClick(event) {
      const clickedLat = event.latLng.lat(); // Lấy vĩ độ
      const clickedLng = event.latLng.lng(); // Lấy kinh độ

      console.log("Vị trí click:", clickedLat, clickedLng);
      this.filter.lat = clickedLat;
      this.filter.lon = clickedLng;
      this.infoWindowPos = { lat: clickedLat, lng: clickedLng };
      this.infoContent = "Tọa độ: " + clickedLat + ", " + clickedLng;
      this.infoWinOpen = true;
    },

    toggleInfoWindow(marker, index) {
      this.infoContent = marker.infoText || "Vị trí hiện tại";
      this.infoWindowPos = marker.position;
      this.infoWinOpen = true;
      this.currentMidx = index;
    },
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
      const payload = {
        status: "PUBLISH",
        detail_address: this.filter.detail_address,
        city: this.filter.city ? this.filter.city.value : null,
        acreage: this.filter.acreage ? this.filter.acreage.value : null,
        rent_fee: this.filter.rent_fee ? this.filter.rent_fee.value : null,
        room_type: this.filter.room_type ? this.filter.room_type.value : null,
        lat: this.filter.lat,
        lon: this.filter.lon,
        radius: this.filter.radius,
      };

      this.$vs.loading();
      postService
        .getPosts(payload)
        .then((response) => {
          const { posts } = response.data;
          this.posts = posts;
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.$vs.loading.close();
        });
    },
    fetchFavorites() {
      this.$vs.loading();
      postService
        .getFavorite()
        .then((response) => {
          const { items } = response.data;
          this.favorites = items;
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
    this.fetchFavorites();

    navigator.geolocation.getCurrentPosition(
      (position) => {
        const userLat = position.coords.latitude;
        const userLng = position.coords.longitude;
        this.center = { lat: userLat, lng: userLng };
        this.userPosition = { lat: userLat, lng: userLng };
      },
      () => {
        this.$vs.notify({
          title: "Thất bại",
          text: "Không thể lấy vị trí",
          iconPack: "feather",
          icon: "icon-alert-circle",
          color: "warning",
        });
      }
    );
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
