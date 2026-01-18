class ApiService {
    constructor() {
        this.token = localStorage.getItem('token')
        this.baseURL = import.meta.env.VITE_API_URL || '/api'
    }

    setToken(token) {
        this.token = token
        localStorage.setItem('token', token)
    }

    clearToken() {
        this.token = null
        localStorage.removeItem('token')
    }

    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`
        const config = {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...options.headers
            },
            ...options
        }

        if (this.token) {
            config.headers.Authorization = `Bearer ${this.token}`
        }

        try {
            const response = await fetch(url, config)
            const data = await response.json()

            if (!response.ok) {
                // Handle 401 Unauthorized
                if (response.status === 401) {
                    this.clearToken()
                    window.location.href = '/login'
                    throw new Error('Session expired. Please login again.')
                }

                // Handle 422 Validation errors
                if (response.status === 422 && data.errors) {
                    const errorMessage = Object.values(data.errors).flat().join(', ')
                    throw new Error(errorMessage || 'Validation failed')
                }

                throw new Error(data.message || 'Request failed')
            }

            return data
        } catch (error) {
            console.error(`API Error [${endpoint}]:`, error)

            // Re-throw with more context
            if (error.name === 'TypeError' && error.message.includes('fetch')) {
                throw new Error('Network error. Please check your connection.')
            }

            throw error
        }
    }

    // Auth endpoints
    async login(credentials) {
        const data = await this.request('/auth/login', {
            method: 'POST',
            body: JSON.stringify(credentials)
        })

        if (data.token) {
            this.setToken(data.token)
        }

        return data
    }

    async logout() {
        try {
            await this.request('/auth/logout', {
                method: 'POST'
            })
        } finally {
            this.clearToken()
        }
    }

    async getUser() {
        return this.request('/auth/user')
    }

    // Dashboard endpoints
    async getDashboardSummary() {
        return this.request('/dashboard/summary')
    }

    async getMonthlyRevenue() {
        return this.request('/dashboard/monthly-revenue')
    }

    async getUpcomingAppointments() {
        return this.request('/dashboard/upcoming-appointments')
    }

    // Client endpoints
    async getClients() {
        return this.request('/clients')
    }

    async getClient(id) {
        return this.request(`/clients/${id}`)
    }

    async createClient(clientData) {
        return this.request('/clients', {
            method: 'POST',
            body: JSON.stringify(clientData)
        })
    }

    async updateClient(id, clientData) {
        return this.request(`/clients/${id}`, {
            method: 'PUT',
            body: JSON.stringify(clientData)
        })
    }

    async deleteClient(id) {
        return this.request(`/clients/${id}`, {
            method: 'DELETE'
        })
    }

    // Service endpoints
    async getServices() {
        return this.request('/services')
    }

    async getService(id) {
        return this.request(`/services/${id}`)
    }

    async createService(serviceData) {
        return this.request('/services', {
            method: 'POST',
            body: JSON.stringify(serviceData)
        })
    }

    async updateService(id, serviceData) {
        return this.request(`/services/${id}`, {
            method: 'PUT',
            body: JSON.stringify(serviceData)
        })
    }

    async deleteService(id) {
        return this.request(`/services/${id}`, {
            method: 'DELETE'
        })
    }

    // Appointment endpoints
    async getAppointments() {
        return this.request('/appointments')
    }

    async getAppointment(id) {
        return this.request(`/appointments/${id}`)
    }

    async createAppointment(appointmentData) {
        return this.request('/appointments', {
            method: 'POST',
            body: JSON.stringify(appointmentData)
        })
    }

    async updateAppointment(id, appointmentData) {
        return this.request(`/appointments/${id}`, {
            method: 'PUT',
            body: JSON.stringify(appointmentData)
        })
    }

    async deleteAppointment(id) {
        return this.request(`/appointments/${id}`, {
            method: 'DELETE'
        })
    }

    async attachServiceToAppointment(appointmentId, serviceId) {
        return this.request(`/appointments/${appointmentId}/services/${serviceId}`, {
            method: 'POST'
        })
    }

    async detachServiceFromAppointment(appointmentId, serviceId) {
        return this.request(`/appointments/${appointmentId}/services/${serviceId}`, {
            method: 'DELETE'
        })
    }

    // Payment endpoints
    async getPayments() {
        return this.request('/payments')
    }

    async getPayment(id) {
        return this.request(`/payments/${id}`)
    }

    async createPayment(paymentData) {
        return this.request('/payments', {
            method: 'POST',
            body: JSON.stringify(paymentData)
        })
    }

    async updatePayment(id, paymentData) {
        return this.request(`/payments/${id}`, {
            method: 'PUT',
            body: JSON.stringify(paymentData)
        })
    }
}

export default new ApiService()
