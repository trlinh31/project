import axios from "../axios";

const userService = {
  async getProfile() {
    return await axios.get("/api/profile");
  },
  getUsers(){
    return  axios.get("/api_admin/users");
  }


};

export default userService;
