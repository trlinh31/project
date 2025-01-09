import axios from "../axios";

const userService = {
  async getProfile() {
    return await axios.get("/api/profile");
  },
  getUsers(){
    return  axios.get("/api_admin/users");
  },
  addUser(payload) {
    return axios.post("/api/register", payload);
  },
  getById(id) {
    return axios.get(`/api_admin/users/${id}`);
  },
  // updatePost(id, payload) {
  //   return axios.put(`/api_admin/posts/${id}`, payload);
  // },
  deleteUser(id) {
    return axios.delete(`/api_admin/posts/${id}`);
  },


};

export default userService;
