<template>
  <div class="container py-10">
    <div class="vx-row mb-base">
      <div class="vx-col md:w-2/3 w-full">
        <vx-card>
          <div class="flex justify-center">
            <swiper
              :options="swiperOption"
              :dir="$vs.rtl ? 'rtl' : 'ltr'"
              :key="$vs.rtl"
            >
              <swiper-slide v-for="(image, index) in room.images" :key="index">
                <img
                  :src="image"
                  class="block mx-auto object-contain"
                  width="500"
                  alt=""
                />
              </swiper-slide>
              <div class="swiper-button-prev" slot="button-prev"></div>
              <div class="swiper-button-next" slot="button-next"></div>
            </swiper>
          </div>
        </vx-card>
      </div>
      <div class="vx-col md:w-1/3 w-full">
        <vx-card>
          <div class="flex flex-col">
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
                  @click="handleAddFavorite"
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
        </vx-card>
      </div>
    </div>

    <div class="vx-row mb-base">
      <div class="vx-col md:w-2/3 w-full">
        <vx-card>
          <h3 class="text-primary font-bold mb-3">{{ room.title }}</h3>
          <div class="flex items-center mb-6 gap-2">
            <span>Địa chỉ:</span>
            <div>
              {{
                room.detail_address +
                ", " +
                room.ward_name +
                ", " +
                room.district_name +
                ", " +
                room.city_name
              }}
            </div>
          </div>
          <div class="flex items-center">
            <div class="text-3xl text-success flex items-center font-bold">
              {{ formatPriceVND(room.rent_fee) }}/tháng
            </div>
            <div class="flex text-lg ml-10 gap-2 items-center">
              <span>Diện tích phòng:</span>
              {{ room.acreage }} m²
            </div>
          </div>
          <vs-divider />
          <h5 class="mb-4 font-bold text-black">Thông tin mô tả</h5>
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
          <h5 class="mb-4 font-bold text-black">Vị trí</h5>
          <gmap-map
            :center="center"
            :zoom="15"
            style="width: 100%; height: 500px"
          >
            <gmap-marker
              :position="center"
              :icon="userPositionIcon"
              :clickable="true"
            ></gmap-marker>
          </gmap-map>
        </vx-card>
      </div>
    </div>

    <div class="vx-row mb-base">
      <div class="vx-col md:w-2/3 w-full">
        <vx-card title="Dành cho bạn">
          <swiper
            :options="swiperOptionSecond"
            :dir="$vs.rtl ? 'rtl' : 'ltr'"
            :key="$vs.rtl"
          >
            <swiper-slide v-for="(item, index) in otherRooms" :key="index">
              <img
                :src="item.images.length > 0 && item.images[0]"
                class="object-cover w-full"
                height="123"
                alt=""
              />
              <p class="truncate text-base">{{ item.title }}</p>
              <p class="text-base font-bold text-danger">
                {{ formatPriceVND(item.rent_fee) }}/tháng
              </p>
            </swiper-slide>
            <map-pin-icon size="1.5x" class="custom-class"></map-pin-icon>
          </swiper>
        </vx-card>
      </div>
    </div>

    <div class="vx-row">
      <div class="vx-col md:w-2/3 w-full">
        <vx-card title="Bình luận">
          <div class="mb-base">
            <form>
              <star-rating
                :rtl="$vs.rtl"
                :star-size="20"
                v-model="rating"
              ></star-rating>
              <vs-textarea placeholder="Nội dung" v-model="content" />
              <vs-button @click.prevent="handleSubmitComment">Gửi</vs-button>
            </form>
          </div>
          <div v-for="comment in comments" :key="comment.id" class="mb-base">
            <div class="flex items-center gap-2">
              <img
                :src="comment.avt || '@/assets/default-user.svg'"
                class="w-16 h-16 rounded-full"
                alt=""
              />
              <div>
                <p>{{ comment.name }}</p>
                <star-rating
                  :rtl="$vs.rtl"
                  :rating="comment.rating"
                  :read-only="true"
                  :star-size="16"
                  :show-rating="false"
                ></star-rating>
                <p>{{ comment.content }}</p>
              </div>
            </div>
          </div>
        </vx-card>
      </div>
    </div>
  </div>
</template>
<script>
import postService from "../services/post.service";
import "swiper/dist/css/swiper.min.css";
import { swiper, swiperSlide } from "vue-awesome-swiper";
import StarRating from "vue-star-rating";

export default {
  data() {
    return {
      room: null,
      otherRooms: [],
      comments: [],
      swiperOption: {
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
      },
      swiperOptionSecond: {
        slidesPerView: 4,
        spaceBetween: 20,
        breakpoints: {
          1024: {
            slidesPerView: 3,
            spaceBetween: 40,
          },
          768: {
            slidesPerView: 2,
            spaceBetween: 30,
          },
          640: {
            slidesPerView: 1,
            spaceBetween: 20,
          },
        },
      },
      center: { lat: 0, lng: 0 },
      userPositionIcon: {
        url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
      },
      content: "",
      rating: 0,
    };
  },
  components: {
    swiper,
    swiperSlide,
    StarRating,
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
          this.center = { lat: +data.lat, lng: +data.lon };
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.$vs.loading.close();
        });
    },
    fetchPosts() {
      this.$vs.loading();
      postService
        .getPosts({})
        .then((response) => {
          const { posts } = response.data;
          this.otherRooms = posts;
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.$vs.loading.close();
        });
    },
    fetchComments(id) {
      this.$vs.loading();
      postService
        .getComments(id)
        .then((response) => {
          const { data } = response;
          this.comments = data;
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.$vs.loading.close();
        });
    },
    handleSubmitComment() {
      if (this.$store.state.auth.isAuthenticated === false) {
        return this.$router.push("/auth/login");
      }

      if (!this.content || !this.rating) {
        this.$vs.notify({
          title: "Cảnh báo",
          text: "Vui lòng nhập các thông tin bắt buộc",
          iconPack: "feather",
          icon: "icon-alert-circle",
          color: "danger",
        });
        return;
      }

      const payload = {
        user_id: this.$store.state.auth.user.id,
        content: this.content,
        post_id: this.room.id,
        rating: this.rating,
      };

      this.$vs.loading();
      postService
        .addComment(payload)
        .then(() => {
          this.$vs.notify({
            title: "Thành công",
            text: "Gửi bình luận thành công",
            iconPack: "feather",
            icon: "icon-check",
            color: "success",
          });
          this.fetchComments(this.$route.params.id);
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.$vs.loading.close();
        });
    },
    handleAddFavorite() {
      if (this.$store.state.auth.isAuthenticated === false) {
        return this.$router.push("/auth/login");
      }

      postService
        .saveFavorite(this.room.id)
        .then(() => {
          this.$vs.notify({
            title: "Thành công",
            text: "Đã thêm vào danh sách yêu thích",
            iconPack: "feather",
            icon: "icon-check",
            color: "success",
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    const id = this.$route.params.id;
    this.fetchPost(id);
    this.fetchPosts();
    this.fetchComments(id);
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
