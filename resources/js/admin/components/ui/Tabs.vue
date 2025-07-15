<template>
    <div>
        <!-- Tabs Navigation -->
        <div class="relative flex gap-4 border-b mb-4">
            <button
                v-for="tab in tabList"
                :key="tab"
                type="button"
                @click="active = tab"
                :class="[
          'relative text-sm px-4 py-2 font-medium transition-colors duration-200 flex items-center gap-2',
          active === tab
            ? 'text-blue-600 dark:text-blue-400'
            : 'text-gray-500 dark:text-gray-400 hover:text-blue-600'
        ]"
            >
                <component
                    v-if="tabIcons[tab]"
                    :is="tabIcons[tab]"
                    class="w-4 h-4"
                />
                {{ $t(tab) }}
                <span
                    class="absolute left-0 -bottom-0.5 h-0.5 w-full bg-blue-600 transition-transform duration-300 scale-x-0 origin-left"
                    :class="{ 'scale-x-100': active === tab }"
                ></span>
            </button>
        </div>

        <!-- Tabs Content -->
        <transition name="fade" mode="out-in">
            <div class="mt-4" :key="active">
                <slot :name="active" />
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, useSlots, watch } from 'vue'
import { Home, Settings, Image as Gallery } from 'lucide-vue-next'

const slots = useSlots()
const tabList = Object.keys(slots)

const STORAGE_KEY = 'active-tab'
const active = ref(localStorage.getItem(STORAGE_KEY) || tabList[0])

watch(active, (val) => {
    localStorage.setItem(STORAGE_KEY, val)
})

const tabIcons = {
    main: Home,
    attributes: Settings,
    images: Gallery
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
