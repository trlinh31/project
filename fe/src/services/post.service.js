import axios from "../axios";

const postService = {
  getPosts() {
    return axios.get("/api_admin/posts");
  },
};

export default postService;
