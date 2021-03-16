import Vue from 'vue'
import VueRouter from 'vue-router'
import UserLoginComponent from "../components/UserLoginComponent";
import CreateEvent from "../components/CreateEvent";
import EventsListComponent from "../components/EventsListComponent";
import AdminLoginComponent from "../components/AdminLoginComponent";

Vue.use(VueRouter)

const routes = [
    {path: '/', redirect: '/login'},
    {path: '/login', component: UserLoginComponent, meta: {requiresGuest: true}},
    {path: '/admin/login', component: AdminLoginComponent, meta: {requiresGuest: true}},
    {path: '/create-event', component: CreateEvent, meta: {requiresUserAuth: true}},
    {path: '/events', component: EventsListComponent, meta: {requiresAdminAuth: true}},
    {path: '*', redirect: '/'}
];
export const router = new VueRouter({
    routes,
    hashing: false,
    mode: "history"
});

router.beforeEach((to, from, next) => {
    let isAuthenticated = localStorage.getItem('auth-token')
    let is_admin = localStorage.getItem('is-admin')

    // check route meta if it requires auth or not
    if (to.matched.some(record => record.meta.requiresUserAuth)) {
        if (isAuthenticated && is_admin == 0) {
            next()

        } else {
            localStorage.removeItem('auth-token')
            localStorage.removeItem('is-admin')
            next({
                path: '/login',
                params: {nextUrl: to.fullPath}
            })
        }
    } else {
        next()
    }
})

router.beforeEach((to, from, next) => {
    let isAuthenticated = localStorage.getItem('auth-token')
    let is_admin = localStorage.getItem('is-admin')

    // check route meta if it requires auth or not
    if (to.matched.some(record => record.meta.requiresAdminAuth)) {
        if (isAuthenticated && is_admin == 1) {
            next()

        } else {
            localStorage.removeItem('auth-token')
            localStorage.removeItem('is-admin')
            next({
                path: '/admin/login',
                params: {nextUrl: to.fullPath}
            })
        }
    } else {
        next()
    }
})


router.beforeEach((to, from, next) => {
    let isAuthenticated = localStorage.getItem('auth-token')
    let is_admin = localStorage.getItem('is-admin')

    // check route meta if it requires auth or not
    if (to.matched.some(record => record.meta.requiresGuest)) {
        if (!isAuthenticated) {
            next()
        } else {
            if (is_admin == 1){
                next({
                    path: '/events',
                    params: {nextUrl: to.fullPath}
                })
            }else {
                next({
                    path: '/create-event',
                    params: {nextUrl: to.fullPath}
                })
            }
        }
    } else {
        next()
    }
})

export default router
