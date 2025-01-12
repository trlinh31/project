<template>
  <form>
    <div class="vx-row">
      <div class="vx-col w-full">
        <div class="w-full mb-base">
          <vx-card>
            <vs-button
              color="primary"
              type="border"
              size="base"
              to="/admin/user/list"
            >
              Hủy
            </vs-button>
            <vs-button
              color="primary"
              type="filled"
              size="base"
              class="ml-2"
              @click.prevent="handleSubmit"
            >
              Lưu thông tin
            </vs-button>
          </vx-card>
        </div>
        <div class="w-full mb-base"></div>
      </div>
      <div class="vx-col w-full">
        <div class="w-full mb-base">
          <vx-card title="Thông tin tài khoản">
            <div class="mb-6">
              <p>Email</p>
              <vs-input
                type="email"
                class="w-full"
                v-model="form.email"
                v-validate="'required|email'"
                name="email"
              />
              <span class="text-danger text-sm" v-show="errors.has('email')">
                {{ errors.first("email") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Họ và tên</p>
              <vs-input
                type="text"
                class="w-full"
                v-model="form.name"
                v-validate="'required'"
                name="name"
              />
              <span class="text-danger text-sm" v-show="errors.has('name')">
                {{ errors.first("name") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Số điện thoại</p>
              <vs-input
                type="number"
                class="w-full"
                v-model="form.phone"
                v-validate="'required'"
                name="phone"
              />
              <span class="text-danger text-sm" v-show="errors.has('phone')">
                {{ errors.first("phone") }}
              </span>
            </div>
          </vx-card>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
import vSelect from "vue-select";
import userService from "@/services/user.service";
import { Validator } from "vee-validate";

const dict = {
  custom: {
    email: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
      email: "Email không đúng định dạng. Vui lòng nhập lại!",
    },
    name: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    phone: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
  },
};

Validator.localize("en", dict);

export default {
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      form: {
        email: "",
        name: "",
        phone: "",
      },
    };
  },
  methods: {
    handleSubmit() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          const payload = {
            ...this.form,
          };

          this.$vs.loading();
          userService
            .updateUser(this.$route.params.id, payload)
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
            .finally(() => {
              this.$vs.loading.close();
            });
        }
      });
    },
    fetchUser(id) {
      this.$vs.loading();
      userService
        .getById(id)
        .then((response) => {
          this.form = response.data;
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
        .finally(() => {
          this.$vs.loading.close();
        });
    },
  },
  mounted() {
    const id = this.$route.params.id;
    this.fetchUser(id);
  },
};
</script>
