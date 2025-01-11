import axios from "../axios";

const authService = {
  login(payload) {
    return axios.post("/api/login", payload);
  },

  register(payload) {
    return axios.post("/api/register", payload);
  },
};

export default authService;
