// store/dashboard.js

export const state = () => ({
  alarm_temparature_latest: null,
  alarm_temparature_hourly: null,
  alarm_humidity_hourly: null,
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
  alarm_temparature_hourly(state, alarm_temparature_hourly) {
    state.alarm_temparature_hourly = alarm_temparature_hourly;
  },
  alarm_humidity_hourly(state, alarm_humidity_hourly) {
    state.alarm_humidity_hourly = alarm_humidity_hourly;
  },
};

export const actions = {
  resetState({ commit }) {
    commit("RESET_STATE");
  },
};
