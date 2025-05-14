import { defineStore } from 'pinia'

export const useJobStore = defineStore('job',{
  state:() => ({
    user:{},
    joblist: [],

    
  }),
  actions:{

  },
});
