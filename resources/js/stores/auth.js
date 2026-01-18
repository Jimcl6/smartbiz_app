import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

export function useAuthStore() {
    const user = ref(null)
    const token = ref(localStorage.getItem('token'))
    const isAuthenticated = computed(() => !!user.value)

    const login = async (credentials) => {
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
                token.value = data.token
                localStorage.setItem('token', data.token)
                return { success: true }
            } else {
                return { success: false, error: data.message }
            }
        } catch (error) {
            return { success: false, error: 'Login failed' }
        }
    }

    const logout = async () => {
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
        } catch (error) {
            return { success: false, error: 'Logout failed' }
        }
    }

    const fetchUser = async () => {
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
        } catch (error) {
            console.error('Failed to fetch user:', error)
        }
    }

    return {
        user,
        token,
        isAuthenticated,
        login,
        logout,
        fetchUser
    }
}
