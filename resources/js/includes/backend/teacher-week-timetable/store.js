import Vuex from "vuex";

export default new Vuex.Store({
  state: {
    data: [],
    isTableLoading:false
  },
  mutations: {
    SETOPTIONS(state, res) {
      Object.keys(res).forEach(item => (state[item] = res[item]));
    },
    SETOPTIONOBJ(state, { key, value }) {
      Object.keys(value).forEach(item => (state[key][item] = value[item]));
    }
  }
});
