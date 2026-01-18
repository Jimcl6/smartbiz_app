import { useAuthStore } from '../stores/auth.js'

export const requireAuth = (to, from, next) => {
    const { isAuthenticated } = useAuthStore()

    if (!isAuthenticated.value) {
        next({
            name: 'login',
            query: { redirect: to.fullPath }
        })
        return false
    }

    next()
}

export const requireGuest = (to, from, next) => {
    const { isAuthenticated } = useAuthStore()

    if (isAuthenticated.value) {
        next(from || { name: 'dashboard' })
        return false
    }

    next()
}
