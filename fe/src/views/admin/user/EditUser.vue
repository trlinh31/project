<template>
  <form>
    <div class="vx-row">
      <div class="vx-col md:w-2/3 w-full">
        <div class="w-full mb-base">
          <vx-card>
            <vs-button color="primary" type="border" size="base" to="/admin/posts">
              Hủy
            </vs-button>
            <vs-button color="primary" type="filled" size="base" class="ml-2" @click.prevent="handleSubmit">
              Lưu thông tin
            </vs-button>
          </vx-card>
        </div>
        <div class="w-full mb-base">

        </div>
        <div class="w-full mb-base">

        </div>
      </div>
      <div class="vx-col  w-full">
        <div class="w-full mb-base">
          <vx-card title="Them user">
            <div class="mb-6">
              <p>Email</p>
              <vs-input type="email" class="w-full" v-model="form.email" v-validate="'required'" name="email" />
              <span class="text-danger text-sm" v-show="errors.has('rent_fee')">
                {{ errors.first("rent_fee") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Name</p>
              <vs-input type="text" class="w-full" v-model="form.name" v-validate="'required'" name="name" />
              <span class="text-danger text-sm" v-show="errors.has('electricity_fee')">
                {{ errors.first("electricity_fee") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Phone</p>
              <vs-input type="number" class="w-full" v-model="form.phone" v-validate="'required'" name="phone" />
              <span class="text-danger text-sm" v-show="errors.has('water_fee')">
                {{ errors.first("water_fee") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Password</p>
              <vs-input type="text" class="w-full" v-model="form.password" v-validate="'required'" name="password" />
            </div>
          </vx-card>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
import vSelect from "vue-select";
import userService from '@/services/user.service';

export default {
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      form: {
        email: "",
        name: "",
        password: "",
        phone: ""
      },
    };
  },
  methods: {
    handleSubmit() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.$vs.loading();
          const payload = {
            ...this.form,
          };

          userService
            .updateUser(this.$route.params.id,payload)
            .then(() => {
              this.$router.push({ name: "admin-user-list" });
            })
            .catch(() => {
              this.$vs.notify({
                title: "Thất bại",
                text: "Sửa thất bại",
                iconPack: "feather",
                icon: "icon-alert-circle",
                color: "warning",
              });
            })
        }
      });
    },
    fetchUser() {
      userService
        .getProfile
        .then((response) => {
          this.form = response.data.user;
        })
        .catch(() => {
          this.$vs.notify({
            title: "Thất bại",
            text: "Lấy thông tin thất bại",
            iconPack: "feather",
            icon: "icon-alert-circle",
            color: "danger",
          });
        })
    },
  },
  mounted() {
    const id = this.$route.params.id;
    this.fetchUser(id);
  },
};

</script>

<style>
.upload-label {
  display: block;
  width: 200px;
  height: 200px;
  border: 1px dashed #ccc;
  cursor: pointer;
}
</style>
