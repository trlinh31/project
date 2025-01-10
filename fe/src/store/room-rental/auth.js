const state = {
  user: null,
  isAuthenticated: !!localStorage.getItem("token"),
};

const mutations = {
  SET_USER(state, user) {
    state.user = user;
  },
  SET_AUTHENTICATED(state, isAuthenticated) {
    state.isAuthenticated = isAuthenticated;
  },
  SET_TOKEN(state, token) {
    localStorage.setItem("token", token);
    state.isAuthenticated = true;
  },
  LOGOUT(state) {
    localStorage.removeItem("token");
    state.isAuthenticated = false;
    state.user = null;
  },
};

const actions = {
  login({ commit }, payload) {
    commit("SET_TOKEN", payload.token);
    commit("SET_USER", payload.user);
    commit("SET_AUTHENTICATED", true);
  },
  logout({ commit }) {
    commit("LOGOUT");
  },
  setUser({ commit }, user) {
    commit("SET_USER", user);
  },
};

const getters = {
  isAuthenticated: (state) => state.isAuthenticated,
  user: (state) => state.user,
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
