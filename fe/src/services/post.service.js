import axios from "../axios";

const postService = {
  getPosts(payload) {
    return axios.post("/api/posts/search", payload);
  },
  addPost(payload) {
    return axios.post("/api_admin/posts", payload);
  },
  getById(id) {
    return axios.get(`/api_admin/posts/${id}`);
  },
  updatePost(id, payload) {
    return axios.put(`/api_admin/posts/${id}`, payload);
  },
  deletePost(id) {
    return axios.delete(`/api_admin/posts/${id}`);
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
  changeStatus(id) {
    return axios.put(`/api_admin/posts/status/${id}`);
  },
  getComments(id) {
    return axios.get(`/api/comment/${id}`);
  },
  addComment(payload) {
    return axios.post(`/api/comment`, payload);
  },
  saveFavorite(id) {
    return axios.get(`/api_admin/posts/save-favorite/${id}`);
  },
  getFavorite() {
    return axios.get(`/api_admin/posts/favorites/all`);
  },
  verifyPost(id) {
    return axios.put(`/api_admin/posts/verify/${id}`);
  },
};

export default postService;
