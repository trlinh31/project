import axios from "../../../axios/index.js";

export default {
  init() {
    axios.interceptors.request.use((config) => {
      const token = localStorage.getItem("token");
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
      return config;
    });

    axios.interceptors.response.use(
      (response) => response,
      async (error) => {
        const { config, response } = error;
        const originalRequest = config;

        if (response && response.status === 401 && !originalRequest._retry) {
          originalRequest._retry = true;
          localStorage.removeItem("token");

          console.warn("Session expired. Redirecting to login...");
          window.location.href = "./auth/login";
        }

        return Promise.reject(error);
      }
    );
  },
};
