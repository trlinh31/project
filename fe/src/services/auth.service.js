import axios from "../axios";

const authService = {
  login(payload) {
    return axios.post("/api/login", payload);
  },

  async register(payload) {
    return await axios.post("/api/register", payload);
  },
};

export default authService;
