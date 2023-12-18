// import { defineStore } from 'pinia'
//
// export const useAuthStore = defineStore('auth', {
//     // arrow function recommended for full type inference
//     state: () => ({
//         currentUser: {},
//         token: ''
//     }),
//     actions: {
//         onCurrentUser(data) {
//             this.currentUser = data
//         },
//         onTokenCreate(data) {
//             this.token = data
//         },
//     },
//     // getters: {
//     //     getToken(){
//     //         return this.token
//     //     },
//     //     getCurrentUser(){
//     //         return this.currentUser
//     //     }
//     // },
// })
// import { reactive } from 'vue'
// export const store = reactive({
//     currentUser: {}
// })