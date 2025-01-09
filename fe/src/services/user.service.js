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
    return axios.get(`/api/profile/${id}`);
  },
  updateUser(id, payload) {
    return axios.put(`/api_admin/users/${id}`, payload);
  },
  deleteUser(id) {
    return axios.delete(`/api_admin/users/${id}`);
  },


};

export default userService;
