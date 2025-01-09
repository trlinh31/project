import axios from "../axios";

const paymentService = {
  vnpay(userId) {
    return axios.post("/api/payment", { user_id: userId });
  },

  vnpayReturn(payload) {
    return axios.post("/api/payment-return", payload);
  },
};

export default paymentService;
