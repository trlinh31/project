import axios from "../axios";

const authService = {
  async login(payload) {
    return await axios.post("/api/login", payload);
  },
};

export default authService;
