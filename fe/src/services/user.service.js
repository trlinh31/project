import axios from "../axios";

const userService = {
  async getProfile() {
    return await axios.get("/api/profile");
  },
};

export default userService;
