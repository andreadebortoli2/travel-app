import { createRouter, createWebHistory } from "vue-router"

import HomeView from "./components/views/HomeView.vue"
import DayView from "./components/views/DayView.vue"
import AddStopView from "./components/views/AddStopView.vue"
import UpdateStopView from "./components/views/UpdateStopView.vue"

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
        },
        {
            path: '/add-stop-to/:id/:date',
            name: 'add-stop',
            component: AddStopView
        },
        {
            path: '/update-stop/:id/:date',
            name: 'update-stop',
            component: UpdateStopView
        },
    ]
})

export default router