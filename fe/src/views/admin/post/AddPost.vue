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
              <label
                for="upload-file"
                class="upload-label"
                v-if="base64Images.length === 0"
              >
                Tải ảnh lên
                <input
                  hidden
                  id="upload-file"
                  type="file"
                  accept="image/*"
                  multiple
                  @change="handleFileChange"
                />
              </label>
              <div v-else>
                <div class="vx-row">
                  <div
                    v-for="(image, index) in base64Images"
                    :key="index"
                    class="vx-col w-1/4 relative"
                  >
                    <img
                      :src="image"
                      class="shadow-md cursor-pointer block object-cover w-full mb-10"
                      height="200"
                      alt="Preview"
                    />
                    <vs-button
                      size="small"
                      type="filled"
                      color="danger"
                      class="absolute top-0 rounded-none"
                      style="right: 14px"
                      @click="removeImage(index)"
                    >
                      Xóa
                    </vs-button>
                  </div>
                </div>
              </div>
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
                v-model="form.detail_address"
                v-validate="'required'"
                name="detail_address"
              />
              <span
                class="text-danger text-sm"
                v-show="errors.has('detail_address')"
              >
                {{ errors.first("detail_address") }}
              </span>
            </div>

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
            </gmap-map>
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
                placeholder="Nhập giá thuê 1 tháng (VNĐ)"
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
                placeholder="Nhập giá tiền điện 1 số (VNĐ)"
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
                placeholder="Nhập giá tiền nước 1 khối (VNĐ)"
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
                placeholder="Nhập giá tiền mạng 1 tháng (VNĐ)"
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
                placeholder="Nhập giá tiền phí dịch vụ 1 tháng (VNĐ)"
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
              <p>Diện tích (m²)</p>
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
              <p>Email liên hệ</p>
              <vs-input
                type="email"
                class="w-full"
                v-validate="'required|email'"
                name="contact_email"
                v-model="form.contact_email"
              />
              <span
                class="text-danger text-sm"
                v-show="errors.has('contact_email')"
              >
                {{ errors.first("contact_email") }}
              </span>
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
import { Validator } from "vee-validate";

const dict = {
  custom: {
    title: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    description: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    city: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    district: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    ward: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    detail_address: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    rent_fee: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    electricity_fee: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    water_fee: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    internet_fee: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    extra_fee: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    room_type: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    furniture: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    acreage: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    contact_name: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
    },
    contact_email: {
      required: "Vui lòng nhập đủ các thông tin bắt buộc",
      email: "Email không đúng định dạng. Vui lòng nhập lại!",
    },
    contact_phone: {
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
        title: "",
        description: "",
        city: null,
        district: null,
        ward: null,
        detail_address: "",
        lat: "0",
        lon: "0",
        room_type: null,
        acreage: null,
        rent_fee: null,
        electricity_fee: null,
        water_fee: null,
        internet_fee: null,
        extra_fee: null,
        furniture: null,
        furniture_detail: "",
        contact_name: "",
        contact_email: "",
        contact_phone: "",
      },
      base64Images: [],
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
          value: "NONE",
        },
        {
          label: "Cơ bản",
          value: "PART",
        },
        {
          label: "Đầy đủ",
          value: "FULL",
        },
      ],
      center: { lat: 0, lng: 0 },
      userPosition: null,
      infoOptions: {
        pixelOffset: { width: 0, height: -35 },
      },
      userPositionIcon:
        "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
      infoContent: "",
      infoWindowPos: null,
      infoWinOpen: false,
      markers: [],
    };
  },
  methods: {
    handleMapClick(event) {
      const lat = event.latLng.lat();
      const lng = event.latLng.lng();

      this.userPosition = { lat, lng };
      this.form.lat = lat;
      this.form.lon = lng;

      this.infoContent = `Tọa độ: Vĩ độ: ${lat}, Kinh độ: ${lng}`;
      this.infoWindowPos = { lat, lng };
      this.infoWinOpen = true;
    },
    toggleInfoWindow(marker, index) {
      this.infoContent = marker.infoText;
      this.infoWindowPos = marker.position;
      this.infoWinOpen = !this.infoWinOpen;
    },
    async handleFileChange(event) {
      const files = event.target.files;
      for (const file of files) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.base64Images.push(e.target.result);
        };
        reader.readAsDataURL(file);
      }
      event.target.value = "";
    },
    removeImage(index) {
      this.base64Images.splice(index, 1);
    },
    convertToBase64(file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        this.base64Image = e.target.result;
        this.form.images = this.base64Image;
      };
      reader.onerror = (error) => {
        console.error("Error reading file:", error);
      };
      reader.readAsDataURL(file);
    },
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
            room_number: 1,
            images: this.base64Images,
          };

          postService
            .addPost(payload)
            .then(() => {
              this.$vs.notify({
                title: "Thành công",
                text: "Thêm bài đăng thành công",
                iconPack: "feather",
                icon: "icon-check",
                color: "success",
              });
              this.$router.push({ name: "admin-post-list" });
            })
            .catch(() => {
              this.$vs.notify({
                title: "Thất bại",
                text: "Thêm bài đăng thất bại",
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
    fetchCities() {
      postService
        .getCities()
        .then((response) => {
          const { items } = response.data;
          this.center = { lat: +items[0].lat, lng: +items[0].lon };
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
          this.center = { lat: +data[0].lat, lng: +data[0].lon };
          console.log(this.center);
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

    navigator.geolocation.getCurrentPosition(
      (position) => {
        const userLat = position.coords.latitude;
        const userLng = position.coords.longitude;
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
.upload-label {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 200px;
  height: 200px;
  border: 1px dashed #ccc;
  cursor: pointer;
}
</style>
