import axios from "axios";

const baseURL = "http://127.0.0.1:8000";

axios.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export default axios.create({
  baseURL,
  headers: {
    "Content-Type": "application/json",
  },
});
