<template>
  <vx-card title="Danh sách tài khoản">
    <vs-table v-if="users.length > 0" max-items="5" pagination :data="users">
      <template slot="thead">
        <vs-th>STT</vs-th>
        <vs-th>Họ tên</vs-th>
        <vs-th>Số điện thoại</vs-th>
        <vs-th>Email</vs-th>
        <vs-th>Trạng thái</vs-th>
        <vs-th>Vai trò</vs-th>
        <vs-th>Hành động</vs-th>
      </template>

      <template slot-scope="{ data }">
        <vs-tr :key="item.id" v-for="(item, index) in data">
          <vs-td :data="item.id">{{ index + 1 }}</vs-td>
          <vs-td :data="item.name" style="max-width: 260px">
            <span>{{ item.name }}</span>
          </vs-td>
          <vs-td :data="item.phone">
            {{ item.phone }}
          </vs-td>
          <vs-td :data="item.email">
            {{ item.email }}
          </vs-td>
          <vs-td :data="item.is_active">
            <vs-chip color="success">Hoạt động</vs-chip>
          </vs-td>
          <vs-td :data="item.role">
            {{ item.role }}
          </vs-td>
          <vs-td>
            <vs-button
              color="primary"
              type="filled"
              size="small"
              :to="`/admin/user/${item.id}`"
            >
              Chỉnh sửa
            </vs-button>
            <vs-button
              color="danger"
              type="filled"
              @click="openAlert(item.id)"
              size="small"
              class="ml-2"
            >
              Xóa
            </vs-button>
          </vs-td>
        </vs-tr>
      </template>
    </vs-table>
    <vs-alert v-else color="warning">Không có bài đăng nào.</vs-alert>
  </vx-card>
</template>

<script>
import "@/assets/scss/vuexy/extraComponents/agGridStyleOverride.scss";
import userService from "@/services/user.service";

export default {
  data: () => ({
    users: [], // Thay đổi từ users thành posts
  }),

  methods: {
    async fetchUser() {
      try {
        const response = await userService.getUsers();
        if (response.data && response.data.data) {
          this.users = response.data.data;
        }
      } catch (error) {
        console.error(error);
      }
    },
    openAlert(id) {
      this.$vs.dialog({
        color: "danger",
        title: "Thông báo",
        text: "Bạn có muốn xóa người dùng này",
        accept: () => this.acceptAlert(id),
      });
    },
    acceptAlert(id) {
      userService.deleteUser(id).then(() => {
        this.fetchUser();
        this.$vs.notify({
          color: "success",
          title: "Thông báo",
          text: "Xóa taì khoảnkhoản thành công",
        });
      });
    },
  },
  mounted() {
    this.fetchUser();
  },
};
</script>

<style lang="scss">
#page-user-list {
  .user-list-filters {
    .vs__actions {
      position: absolute;
      right: 0;
      top: 50%;
      transform: translateY(-58%);
    }
  }
}
</style>
