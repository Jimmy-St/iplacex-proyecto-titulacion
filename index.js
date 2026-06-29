import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Layouts
import AppLayout from '@/layouts/AppLayout.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'

// Vistas
import LoginView from '@/views/login/LoginView.vue'
import TicketsLayoutView from '../views/tickets/TicketsLayoutView.vue'
import TicketDetailView from '../views/tickets/TicketLayoutView.vue' // Asumo que es TicketLayoutView.vue
import PickersListView from '@/views/pickers/PickersListView.vue'
import PickerDetailView from '@/views/pickers/PickerDetailView.vue'
import OtherView from '@/views/OtherView.vue'
import ConfigView from '@/views/ConfigView.vue'

const routes = [
    {
        path: '/auth',
        component: AuthLayout,
        children: [
            { path: '/login', name: 'login', component: LoginView }
        ]
    },
    {
        path: '/',
        component: AppLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '', redirect: '/tickets' },
            { path: 'tickets', name: 'tickets-list', component: TicketsLayoutView },
            { path: 'tickets/:id', name: 'ticket-detail', component: TicketDetailView, props: true },
            { path: 'pickers', name: 'pickers-list', component: PickersListView },
            { path: 'pickers/:id', name: 'picker-detail', component: PickerDetailView, props: true },
            { path: 'other', name: 'other', component: OtherView },
            { path: 'config', name: 'config', component: ConfigView },
        ]
    }
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
})

// Guardia de Navegación
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()

    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)

    if (requiresAuth && !authStore.isAuthenticated) {
        // Si la ruta requiere autenticación y el usuario no está logueado, redirigir a /login
        next({ name: 'login' })
    } else if (to.name === 'login' && authStore.isAuthenticated) {
        // Si el usuario ya está logueado e intenta ir al login, redirigir a la home
        next({ path: '/tickets' })
    } else {
        // En cualquier otro caso, permitir la navegación
        next()
    }
})

export default router