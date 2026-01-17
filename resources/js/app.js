import './bootstrap';
import { createApp } from 'vue';

const app = createApp({
    data() {
        return {
            message: 'SmartBiz - Vue 3 + Laravel SPA'
        }
    },
    template: `
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-center mb-8">{{ message }}</h1>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Welcome to SmartBiz</h2>
                <p class="text-gray-600">Your Vue 3 SPA is now running with Laravel backend!</p>
            </div>
        </div>
    `
});

app.mount('#app');
