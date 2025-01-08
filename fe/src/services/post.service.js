import axios from "../axios";

const postService = {
  getPosts() {
    return axios.get("/api_admin/posts");
  },
  addPost(payload) {
    return axios.post("/api_admin/posts", payload);
  },
  getCities() {
    return axios.get("/api/cities");
  },
  getDistricts(id) {
    return axios.get("/api/districts", { params: { id } });
  },
  getWards(id) {
    return axios.get("/api/wards", { params: { id } });
  },
};

export default postService;
