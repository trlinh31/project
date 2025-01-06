<template>
  <vx-card title="Danh sách bài đăng">
    <vs-table max-items="5" pagination :data="posts">
      <template slot="thead">
        <vs-th>Id</vs-th>
        <vs-th>Tiêu đề</vs-th>
        <!-- <vs-th>Ảnh</vs-th> -->
        <vs-th>Tiền thuê phòng</vs-th>
        <vs-th>Trạng thái</vs-th>
        <vs-th>Ngày đăng</vs-th>
        <vs-th>Hành động</vs-th>
      </template>

      <template slot-scope="{ data }">
        <vs-tr :key="item.id" v-for="item in data">
          <vs-td :data="item.id">
            {{ item.id }}
          </vs-td>

          <vs-td :data="item.title" style="max-width: 260px">
            <span>{{ item.title }}</span>
          </vs-td>

          <!-- <vs-td :data="item.images">
            <img :src="item.images" height="200" alt="" />
          </vs-td> -->

          <vs-td :data="item.rent_fee">
            {{ formatPriceVND(item.rent_fee) }}
          </vs-td>

          <vs-td :data="item.status">
            <vs-chip color="warning">
              {{ item.status }}
            </vs-chip>
          </vs-td>

          <vs-td :data="item.created_at">
            {{ convertDate(item.created_at) }}
          </vs-td>

          <vs-td :data="item.created_at">
            <vs-button color="primary" type="filled" size="small">
              Chỉnh sửa
            </vs-button>
            <vs-button color="warning" type="filled" size="small" class="ml-2">
              AD
            </vs-button>
            <vs-button color="danger" type="filled" size="small" class="ml-2">
              Xóa
            </vs-button>
          </vs-td>
        </vs-tr>
      </template>
    </vs-table>
  </vx-card>
</template>

<script>
import postService from "../../../services/post.service";

export default {
  data: () => ({
    posts: [],
  }),
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
    fetchPosts() {
      this.$vs.loading();
      postService
        .getPosts()
        .then((response) => {
          const { data } = response.data;
          this.posts = data;
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
    this.fetchPosts();
  },
};
</script>

<style scoped>
.ml-2 {
  margin-left: 2px;
}
</style>
