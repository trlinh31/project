<template>
  <vx-card title="Danh sách bài đăng">
    <form class="vx-row mb-base">
      <div class="vx-col w-1/4">
        <v-select :options="roomTypes" v-model="filter.room_type" placeholder="Loại phòng" />
      </div>
      <div class="vx-col w-1/4">
        <v-select :options="priceRange" v-model="filter.rent_fee" placeholder="Giá thuê phòng" />
      </div>
      <div class="vx-col w-1/4">
        <v-select :options="areaRange" v-model="filter.acreage" placeholder="Diện tích" />
      </div>
      <div class="vx-col w-1/4">
        <vs-button @click.prevent="fetchPosts">Tìm kiếm</vs-button>
      </div>
    </form>
    <vs-table max-items="5" pagination :data="posts">
      <template slot="thead">
        <vs-th>Id</vs-th>
        <vs-th>Tiêu đề</vs-th>
        <vs-th>Ảnh</vs-th>
        <vs-th>Tiền thuê phòng</vs-th>
        <vs-th>Loại phòng</vs-th>
        <vs-th>Diện tích</vs-th>
        <vs-th>Trạng thái</vs-th>
        <vs-th>Ngày đăng</vs-th>
        <vs-th>Xác nhận bài đăng</vs-th>
        <vs-th>Hành động</vs-th>
      </template>

      <template slot-scope="{ data }">
        <vs-tr :key="item.id" v-for="(item, index) in data">
          <vs-td :data="item.id">
            {{ index + 1 }}
          </vs-td>

          <vs-td :data="item.title" style="max-width: 260px">
            <span>{{ item.title }}</span>
          </vs-td>

          <vs-td :data="item.images">
            <img :src="item.images.length > 0 && item.images[0]" width="100" height="150" class="object-cover" alt="" />
          </vs-td>

          <vs-td :data="item.rent_fee">
            {{ formatPriceVND(item.rent_fee) }}
          </vs-td>

          <vs-td :data="item.room_type">
            {{ getRoomLabel(item.room_type) }}
          </vs-td>

          <vs-td :data="item.acreage"> {{ item.acreage }} m<sup>2</sup> </vs-td>

          <vs-td :data="item.status">
            <vs-chip color="warning">
              {{ item.status }}
            </vs-chip>
          </vs-td>

          <vs-td :data="item.created_at">
            {{ convertDate(item.created_at) }}
          </vs-td>

          <vs-td>
            <vs-checkbox :checked="item.is_verify === 1" @change="handleVerify(item.id, $event)">
              Đã kiểm duyệt
            </vs-checkbox>
          </vs-td>

          <vs-td>
            <div class="flex flex-col gap-y-2">
              <vs-button v-if="activeUserInfo && activeUserInfo.role !== 'ADMIN'" color="primary" type="filled"
                size="small" :to="`/admin/post/${item.id}`">
                Chỉnh sửa
              </vs-button>
              <vs-button color="warning" type="filled" size="small" @click="openAlertStatus(item.id)" v-if="
                activeUserInfo &&
                activeUserInfo.role === 'ADMIN' &&
                item.status !== 'PUBLISH'
              ">
                Phê duyệt
              </vs-button>

              <vs-button v-if="activeUserInfo && activeUserInfo.role !== 'ADMIN'" color="danger" type="filled"
                @click="openAlert(item.id)" size="small">
                Xóa
              </vs-button>
            </div>
          </vs-td>
        </vs-tr>
      </template>
    </vs-table>
  </vx-card>
</template>

<script>
import postService from "../../../services/post.service";
import vSelect from "vue-select";

export default {
  components: {
    "v-select": vSelect,
  },
  data: () => ({
    posts: [],
    cities: [],
    filter: {
      room_type: null,
      rent_fee: null,
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
  }),
  computed: {
    activeUserInfo() {
      return this.$store.state.auth.user;
    },
    getRoomLabel() {
      return (roomType) =>
        this.roomTypes.find((type) => type.value === roomType).label ||
        "Không xác định";
    },
  },
  methods: {
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
    handleVerify(id) {
      postService.verifyPost(id)
        .then(() => {
          this.$vs.notify({
            color: "success",
            title: "Thành công",
            text: "Bài đăng đã được kiểm duyệt.",
          });
        })
        .catch(err => {
          this.$vs.notify({
            color: "danger",
            title: "Lỗi",
            text: "Có lỗi xảy ra khi kiểm duyệt bài đăng.",
          });
        });
    },
    fetchPosts() {
      const payload = {
        acreage: this.filter.acreage ? this.filter.acreage.value : null,
        rent_fee: this.filter.rent_fee ? this.filter.rent_fee.value : null,
        room_type: this.filter.room_type ? this.filter.room_type.value : null,
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
    openAlert(id) {
      this.$vs.dialog({
        color: "danger",
        title: "Thông báo",
        text: "Bạn có muốn xóa bài đăng này?",
        accept: () => this.acceptAlert(id),
      });
    },
    openAlertStatus(id) {
      this.$vs.dialog({
        color: "danger",
        title: "Thông báo",
        text: "Bạn có muốn public bài đăng này?",
        accept: () => this.acceptAlertStatus(id),
      });
    },
    acceptAlert(id) {
      postService.deletePost(id).then(() => {
        this.fetchPosts();
        this.$vs.notify({
          color: "success",
          title: "Thông báo",
          text: "Xóa bài đăng thành công",
        });
      });
    },
    acceptAlertStatus(id) {
      postService.changeStatus(id).then(() => {
        this.fetchPosts();
        this.$vs.notify({
          color: "success",
          title: "Thông báo",
          text: "Thay đổi trạng thái bài viết thành công",
        });
      });
    },
  },
  mounted() {
    this.fetchPosts();
  },
};
</script>

<style scoped>
.ml-2 {
  margin-left: 2px;
}
</style>
