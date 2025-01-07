import axios from "../axios";

const userService = {
  async getProfile() {
    return await axios.get("/api/profile");
  },
  async getUser(){
    return await axios.get("/api/user");
  }


};

export default userService;
