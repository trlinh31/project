import axios from "../../../axios/index.js";
import store from "../../../../store/store.js";

// Token Refresh
let isAlreadyFetchingAccessToken = false;
let subscribers = [];

function onAccessTokenFetched(newAccessToken) {
  subscribers.forEach((callback) => callback(newAccessToken));
  subscribers = [];
}

function addSubscriber(callback) {
  subscribers.push(callback);
}

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
          window.location.href = "./pages/login";
        }

        return Promise.reject(error);
      }
    );
  },
  login(email, pwd) {
    return axios.post("/api/auth/login", { email, password: pwd });
  },
  registerUser(name, email, pwd) {
    return axios.post("/api/auth/register", {
      displayName: name,
      email,
      password: pwd,
    });
  },
  refreshToken() {
    return axios.post("/api/auth/refresh-token", {
      accessToken: localStorage.getItem("token"),
    });
  },
};
