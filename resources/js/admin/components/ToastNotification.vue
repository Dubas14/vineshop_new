<!-- ToastNotification.vue -->
<template>
    <div
        v-if="visible"
        :class="[
      'fixed top-4 right-4 px-4 py-3 rounded shadow text-white z-50 transition-opacity duration-300',
      type === 'success' ? 'bg-green-500' :
      type === 'error' ? 'bg-red-500' :
      'bg-gray-500'
    ]"
    >
        <div class="flex justify-between items-center space-x-4">
            <span>{{ message }}</span>
            <button @click="close" class="text-white text-xl leading-none">&times;</button>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    type: { type: String, default: 'success' }, // success | error | info
    message: { type: String, required: true }
})

const emit = defineEmits(['close'])
const visible = ref(true)

const close = () => {
    visible.value = false
    emit('close')
}

// Автоматично зникне через 3 секунди
setTimeout(close, 3000)
</script>
