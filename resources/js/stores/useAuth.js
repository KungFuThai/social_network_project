import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
    // arrow function recommended for full type inference
    state: () => {
        return {
            // all these properties will have their type inferred automatically
            currentUser: {},
            token: null,
        }
    },
    actions: {
        onCurrentUser(data) {
            this.currentUser = data
        },
        onTokenCreate(data) {
            this.token = data
        },
    },
    // getters: {
    //     getToken(){
    //         return this.token
    //     },
    //     getCurrentUser(){
    //         return this.currentUser
    //     }
    // },
})