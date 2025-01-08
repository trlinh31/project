<template>
  <form>
    <div class="vx-row">
      <div class="vx-col md:w-2/3 w-full">
        <div class="w-full mb-base">
          <vx-card>
            <vs-button
              color="primary"
              type="border"
              size="base"
              to="/admin/posts"
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
        <div class="w-full mb-base">
          <vx-card title="Thông tin chung">
            <div class="mb-6">
              <vs-upload
                automatic
                :accept="'image/*'"
                text="Ảnh bài viết"
                action=""
              />
            </div>
            <div class="mb-6">
              <p>Tiêu đề</p>
              <vs-input
                class="w-full"
                v-validate="'required'"
                name="title"
                v-model="form.title"
              />
              <span class="text-danger text-sm" v-show="errors.has('title')">
                {{ errors.first("title") }}
              </span>
            </div>
            <div>
              <p>Mô tả</p>
              <vs-textarea
                v-model="form.description"
                v-validate="'required'"
                name="description"
                placeholder="Mô tả chi tiết về:
                  • Loại hình bất động sản
                  • Vị trí
                  • Diện tích, tiện ích
                  • Tình trạng nội thất
                  ...
                  (VD: Khu nhà có vị trí thuận lợi, gần công viên, trường học...)
                  "
                rows="8"
              />
              <span
                class="text-danger text-sm"
                v-show="errors.has('description')"
              >
                {{ errors.first("description") }}
              </span>
            </div>
          </vx-card>
        </div>
        <div class="w-full mb-base">
          <vx-card title="Địa chỉ">
            <div class="mb-6">
              <p>Tỉnh/Thành phố</p>
              <v-select
                :options="cities"
                v-model="form.city"
                v-validate="'required'"
                name="city"
                @input="onCityChange"
              />
              <span class="text-danger text-sm" v-show="errors.has('city')">
                {{ errors.first("city") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Quận/Huyện</p>
              <v-select
                :options="districts"
                v-model="form.district"
                v-validate="'required'"
                name="district"
                @input="onDistrictChange"
              />
              <span class="text-danger text-sm" v-show="errors.has('district')">
                {{ errors.first("district") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Phường/Xã</p>
              <v-select
                :options="wards"
                v-model="form.ward"
                v-validate="'required'"
                name="ward"
              />
              <span class="text-danger text-sm" v-show="errors.has('ward')">
                {{ errors.first("ward") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Địa chỉ</p>
              <vs-input
                class="w-full"
                v-model="form.address"
                v-validate="'required'"
                name="address"
              />
              <span class="text-danger text-sm" v-show="errors.has('address')">
                {{ errors.first("address") }}
              </span>
            </div>
          </vx-card>
        </div>
      </div>
      <div class="vx-col md:w-1/3 w-full">
        <div class="w-full mb-base">
          <vx-card title="Thiết lập giá">
            <div class="mb-6">
              <p>Giá thuê</p>
              <vs-input
                type="number"
                class="w-full"
                v-model="form.rent_fee"
                v-validate="'required'"
                name="rent_fee"
              />
              <span class="text-danger text-sm" v-show="errors.has('rent_fee')">
                {{ errors.first("rent_fee") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Giá điện</p>
              <vs-input
                type="number"
                class="w-full"
                v-model="form.electricity_fee"
                v-validate="'required'"
                name="electricity_fee"
              />
              <span
                class="text-danger text-sm"
                v-show="errors.has('electricity_fee')"
              >
                {{ errors.first("electricity_fee") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Giá nước</p>
              <vs-input
                type="number"
                class="w-full"
                v-model="form.water_fee"
                v-validate="'required'"
                name="water_fee"
              />
              <span
                class="text-danger text-sm"
                v-show="errors.has('water_fee')"
              >
                {{ errors.first("water_fee") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Cước internet</p>
              <vs-input
                type="number"
                class="w-full"
                v-model="form.internet_fee"
                v-validate="'required'"
                name="internet_fee"
              />
              <span
                class="text-danger text-sm"
                v-show="errors.has('internet_fee')"
              >
                {{ errors.first("internet_fee") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Phí dịch vụ</p>
              <vs-input
                type="number"
                class="w-full"
                v-model="form.extra_fee"
                v-validate="'required'"
                name="extra_fee"
              />
              <span
                class="text-danger text-sm"
                v-show="errors.has('extra_fee')"
              >
                {{ errors.first("extra_fee") }}
              </span>
            </div>
          </vx-card>
        </div>

        <div class="w-full mb-base">
          <vx-card title="Địa chỉ">
            <div class="mb-6">
              <p>Loại phòng</p>
              <v-select
                :options="roomTypes"
                v-model="form.room_type"
                v-validate="'required'"
                name="room_type"
              />
              <span
                class="text-danger text-sm"
                v-show="errors.has('room_type')"
              >
                {{ errors.first("room_type") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Nội thất</p>
              <v-select
                :options="furnitureTypes"
                v-model="form.furniture"
                v-validate="'required'"
                name="furniture"
              />
              <span
                class="text-danger text-sm"
                v-show="errors.has('furniture')"
              >
                {{ errors.first("furniture") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Diện tích</p>
              <vs-input
                type="number"
                class="w-full"
                v-model="form.acreage"
                v-validate="'required'"
                name="acreage"
              />
              <span class="text-danger text-sm" v-show="errors.has('acreage')">
                {{ errors.first("acreage") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Số lượng phòng</p>
              <vs-input
                class="w-full"
                type="number"
                v-model="form.room_number"
                v-validate="'required'"
                name="room_number"
              />
              <span
                class="text-danger text-sm"
                v-show="errors.has('room_number')"
              >
                {{ errors.first("room_number") }}
              </span>
            </div>
          </vx-card>
        </div>

        <div class="w-full mb-base">
          <vx-card title="Thông tin liên hệ">
            <div class="mb-6">
              <p>Tên liên hệ</p>
              <vs-input
                class="w-full"
                v-model="form.contact_name"
                v-validate="'required'"
                name="contact_name"
              />
              <span
                class="text-danger text-sm"
                v-show="errors.has('contact_name')"
              >
                {{ errors.first("contact_name") }}
              </span>
            </div>
            <div class="mb-6">
              <p>Email liên hệ (không bắt buộc)</p>
              <vs-input
                type="email"
                class="w-full"
                v-model="form.contact_email"
              />
            </div>
            <div class="mb-6">
              <p>Số điện thoại</p>
              <vs-input
                type="tel"
                class="w-full"
                v-model="form.contact_phone"
                v-validate="'required'"
                name="contact_phone"
              />
              <span
                class="text-danger text-sm"
                v-show="errors.has('contact_phone')"
              >
                {{ errors.first("contact_phone") }}
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
import postService from "../../../services/post.service";

export default {
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      form: {
        title: "",
        description: "",
        city: null,
        district: null,
        ward: null,
        address: "",
        rent_fee: null,
        electricity_fee: null,
        water_fee: null,
        internet_fee: null,
        extra_fee: null,
        images: null,
        contact_name: "",
        contact_email: "",
        contact_phone: "",
        room_type: null,
        acreage: null,
        furniture: null,
        room_number: 1,
      },
      cities: [],
      districts: [],
      wards: [],
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
      furnitureTypes: [
        {
          label: "Không có",
          value: "unfurnished",
        },
        {
          label: "Cơ bản",
          value: "partially_furnished",
        },
        {
          label: "Đầy đủ",
          value: "furnished",
        },
      ],
    };
  },
  methods: {
    handleSubmit() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.$vs.loading();
          const payload = {
            ...this.form,
            city: this.form.city.value,
            district: this.form.district.value,
            ward: this.form.ward.value,
            room_type: this.form.room_type.value,
            furniture: this.form.furniture.value,
            status: "PENDING",
            detail_address: this.form.address,
            lat: 0,
            lon: 0,
          };

          postService
            .addPost(payload)
            .then((response) => {
              this.$vs.notify({
                title: "Thêm bài đăng",
                text: "Thêm bài đăng thành công",
                iconPack: "feather",
                icon: "icon-check",
                color: "success",
              });
              this.$router.push({ name: "admin-post-list" });
            })
            .catch((error) => {
              this.$vs.notify({
                title: "Error",
                text: error.message,
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
    resetForm() {
      this.form = {
        title: "",
        description: "",
        city: null,
        district: null,
        ward: null,
        address: "",
        rent_fee: null,
        electricity_fee: null,
        water_fee: null,
        internet_fee: null,
        extra_fee: null,
        images: null,
        contact_name: "",
        contact_email: "",
        contact_phone: "",
        room_type: null,
        acreage: null,
        furniture: null,
        room_number: 1,
      };
      this.$validator.reset();
    },
    fetchCities() {
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
        });
    },
    fetchDistricts(id) {
      postService
        .getDistricts(id)
        .then((response) => {
          const { data } = response.data;
          this.districts = data.map((item) => ({
            label: item.name,
            value: item.id,
          }));
        })
        .catch((error) => {
          console.log(error);
        });
    },
    fetchWards(id) {
      postService
        .getWards(id)
        .then((response) => {
          const { data } = response.data;
          this.wards = data.map((item) => ({
            label: item.name,
            value: item.id,
          }));
        })
        .catch((error) => {
          console.log(error);
        });
    },
    onCityChange(city) {
      this.districts = [];
      this.wards = [];
      this.fetchDistricts(city.value);
    },
    onDistrictChange(district) {
      this.wards = [];
      this.fetchWards(district.value);
    },
  },
  mounted() {
    this.fetchCities();
    // this.fetchDistricts();
    // this.fetchWards();
  },
};
</script>
