import axios from "../axios";

const emailService = {
  sendEmailVerify(email) {
    return axios.post("/api/send-verification-email", { email });
  },

  verifyEmail(payload) {
    return axios.post("/api/verify-email", payload);
  },
};

export default emailService;
