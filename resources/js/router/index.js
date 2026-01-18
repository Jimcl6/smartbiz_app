import { createRouter, createWebHistory } from 'vue-router'
import { requireAuth } from './guards.js'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'dashboard',
            component: () => import('../views/Dashboard.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('../views/auth/Login.vue'),
            meta: { requiresAuth: false }
        },
        {
            path: '/clients',
            name: 'clients',
            component: () => import('../views/clients/Clients.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/clients/create',
            name: 'client-create',
            component: () => import('../views/clients/ClientForm.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/clients/:id',
            name: 'client-details',
            component: () => import('../views/clients/ClientDetails.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/services',
            name: 'services',
            component: () => import('../views/services/Services.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/services/create',
            name: 'service-create',
            component: () => import('../views/services/ServiceForm.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/appointments',
            name: 'appointments',
            component: () => import('../views/appointments/Appointments.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/appointments/create',
            name: 'appointment-create',
            component: () => import('../views/appointments/AppointmentForm.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/appointments/calendar',
            name: 'calendar',
            component: () => import('../views/appointments/Calendar.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/payments',
            name: 'payments',
            component: () => import('../views/payments/Payments.vue'),
            meta: { requiresAuth: true }
        }
    ]
})

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth) {
        requireAuth(to, from, next)
    } else if (to.meta.requiresGuest) {
        requireGuest(to, from, next)
    } else {
        next()
    }
})

export default router
