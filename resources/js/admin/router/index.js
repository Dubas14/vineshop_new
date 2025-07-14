import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Products from '../views/products/Index.vue';
import Orders from '../views/orders/Index.vue';
import Categories from '../views/categories/Index.vue';
import Banners from '../views/banners/Index.vue';
import CreateProduct from '../views/products/Create.vue';
import Clients from '../views/pages/Clients.vue'

const routes = [
    { path: '/admin/login', name: 'login', component: Login },
    { path: '/admin/products', name: 'products', component: Products },
    { path: '/admin/orders', name: 'orders', component: Orders },
    { path: '/admin/categories', name: 'categories', component: Categories },
    { path: '/admin/banners', name: 'banners', component: Banners },
    {
        path: '/admin/banners/:id/edit',
        name: 'banners.edit',
        component: () => import('../views/banners/Edit.vue')
    },
    { path: '/admin/products/create', name: 'product-create', component: CreateProduct },
    {
        path: '/admin/dashboard',
        name: 'dashboard',
        component: () => import('@/admin/components/Dashboard.vue'),
    },
    {
        path: '/admin/products/:id/edit',
        name: 'products.edit',
        component: () => import('@/admin/views/products/Edit.vue'),
    },
    {
        path: '/admin/banners/create',
        name: 'banners.create',
        component: () => import('../views/banners/Create.vue')
    },
    {
        path: '/cart',
        name: 'cart',
        component: () => import('@/admin/views/Cart.vue')
    },
    {
        path: '/admin/orders/:id',
        name: 'orders.show',
        component: () => import('@/admin/views/orders/Show.vue')
    },
    {
        path: '/admin/import-products',
        name: 'ImportProducts',
        component: () => import('@/admin/views/products/ImportProducts.vue'),
        meta: { requiresAuth: true },
    },

    {
        path: '/admin/import-images',
        name: 'import-images',
        component: () => import('@/admin/views/products/ImportImages.vue'),
        meta: { requiresAuth: true }
    },

    { path: '/admin/categories', name: 'categories', component: Categories },
    {
        path: '/admin/categories/create',
        name: 'categories.create',
        component: () => import('@/admin/views/categories/Create.vue'),
    },
    {
        path: '/admin/categories/:id/edit',
        name: 'categories.edit',
        component: () => import('@/admin/views/categories/Edit.vue'),
    },
    {
        path: '/admin/clients',
        component: Clients,
        name: 'admin.clients',
        meta: { requiresAuth: true },
    },

];


const deleteClient = async (id) => {
    if (!confirm("Видалити клієнта?")) return;
    await axios.delete(`/api/admin/clients/${id}`);
    clients.value = clients.value.filter(c => c.id !== id);
};
const saveDiscount = async (client) => {
    try {
        await axios.put(`/api/admin/clients/${client.id}`, {
            discount: client.discount,
        });
        // Покажи тост, наприклад:
        alert('Знижку оновлено!');
    } catch (e) {
        alert('Помилка при збереженні!');
    }
};

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
