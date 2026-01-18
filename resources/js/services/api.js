const API_BASE_URL = '/api'

class ApiService {
    constructor() {
        this.token = localStorage.getItem('token')
    }

    async request(endpoint, options = {}) {
        const url = `${API_BASE_URL}${endpoint}`
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

        const response = await fetch(url, config)
        const data = await response.json()

        if (!response.ok) {
            throw new Error(data.message || 'Request failed')
        }

        return data
    }

    // Auth endpoints
    async login(credentials) {
        return this.request('/auth/login', {
            method: 'POST',
            body: JSON.stringify(credentials)
        })
    }

    async logout() {
        return this.request('/auth/logout', {
            method: 'POST'
        })
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
