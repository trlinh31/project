import axios from "../axios";

const authService = {
  async login(payload) {
    return await axios.post("/api/login", payload);
  },

  async register(payload) {
    return await axios.post("/api/register", payload);
  },
};

export default authService;
