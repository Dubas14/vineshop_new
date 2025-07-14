<template>
    <div class="admin-layout">
        <Sidebar />
        <div class="main-content">
            <router-view />
        </div>
    </div>
</template>

<script setup>
import Sidebar from './components/Sidebar.vue';
</script>

<style>
.admin-layout {
    display: flex;
}


.main-content {
    flex: 1;
    margin-left: 250px;
    padding: 25px;
    min-height: 100vh;
    background-color: #f5f7fa;
    transition: margin-left 0.3s ease;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 70px;
    }
}
</style>
