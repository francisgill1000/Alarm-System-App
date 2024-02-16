// store/dashboard.js

export const state = () => ({
  alarm_temparature_latest: null,
});

export const mutations = {
  RESET_STATE(state) {
    Object.keys(state).forEach((key) => {
      state[key] = null;
    });

    //  console.log("all mutations cleared", state);
  },
  alarm_temparature_latest(state, alarm_temparature_latest) {
    state.alarm_temparature_latest = alarm_temparature_latest;
  },
};

export const actions = {
  resetState({ commit }) {
    commit("RESET_STATE");
  },
};
