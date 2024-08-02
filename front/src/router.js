import { createRouter, createWebHistory } from "vue-router"

import HomeView from "./components/views/HomeView.vue"
import DayView from "./components/views/DayView.vue"

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView
        },
        {
            path: '/day/:id/:date',
            name: 'day',
            component: DayView
        }
    ]
})

export default router