import axios from "axios";

const baseURL = "http://127.0.0.1:8000";





const apiClient = axios.create({
  baseURL,
  headers: {
    "Content-Type": "application/json",
  },
});



// axios.interceptors.request.use((config) => {
//   const token = localStorage.getItem("token");
//   console.log("token", token);

//   if (token) {
//     config.headers.Authorization = `Bearer ${token}`;
//   }
//   return config;
// });

// axios.interceptors.response.use(
//   (response) => {
//     return response;
//   },
//   (error) => {
//     if (error.response.status === 401) {
//       localStorage.removeItem("token");
//     }
//     return Promise.reject(error);
//   }
// );
