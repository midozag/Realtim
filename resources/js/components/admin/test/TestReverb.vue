<template>
  <div class="p-4">
    <h2 class="text-xl font-bold mb-4">Laravel 12 + Vue 3 + Reverb Test</h2>
    <div v-if="messages.length === 0" class="text-gray-500">
      Waiting for messages...
    </div>
    <div v-for="message in messages" :key="message.id" class="mb-2 p-2 bg-green-100 rounded">
      {{ message.text }} - {{ message.time }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const messages = ref([])
console.log(messages.value);

onMounted(() => {
  window.Echo.channel('test-channel')
    .listen('MessageSent', (e) => {
      messages.value.push({
        id: Date.now(),
        text: e.message,
        time: new Date().toLocaleTimeString()
      })
    })
})

onUnmounted(() => {
  window.Echo.leave('test-channel')
})
</script>