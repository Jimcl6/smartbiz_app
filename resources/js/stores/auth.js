import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

export function useAuthStore() {
    const user = ref(null)
    const token = ref(localStorage.getItem('token'))
    const loading = ref(false)
    const error = ref('')

    const isAuthenticated = computed(() => !!user.value && !!token.value)

    const login = async (credentials) => {
        loading.value = true
        error.value = ''

        try {
            const response = await fetch('/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(credentials)
            })

            const data = await response.json()

            if (response.ok) {
                user.value = data.user
                token.value = data.token || null
                if (token.value) {
                    localStorage.setItem('token', token.value)
                }
                return { success: true }
            } else {
                error.value = data.message || 'Login failed'
                return { success: false, error: data.message }
            }
        } catch (err) {
            error.value = 'Network error. Please try again.'
            return { success: false, error: 'Network error' }
        } finally {
            loading.value = false
        }
    }

    const logout = async () => {
        loading.value = true

        try {
            await fetch('/api/auth/logout', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })

            user.value = null
            token.value = null
            localStorage.removeItem('token')
            return { success: true }
        } catch (err) {
            error.value = 'Logout failed'
            return { success: false, error: 'Logout failed' }
        } finally {
            loading.value = false
        }
    }

    const fetchUser = async () => {
        if (!token.value) return

        loading.value = true

        try {
            const response = await fetch('/api/auth/user', {
                headers: {
                    'Authorization': `Bearer ${token.value}`,
                    'Accept': 'application/json'
                }
            })

            if (response.ok) {
                const data = await response.json()
                user.value = data.user
            }
        } catch (err) {
            console.error('Failed to fetch user:', err)
        } finally {
            loading.value = false
        }
    }

    const clearError = () => {
        error.value = ''
    }

    return {
        user,
        token,
        loading,
        error,
        isAuthenticated,
        login,
        logout,
        fetchUser,
        clearError
    }
}
