<template>
    <div class="min-h-screen bg-gray-100">
      
      <Navbar/>
      <main class="p-6">
         <div class="max-w-7xl mx-auto">
            <slot></slot>
         </div>
      </main>
    </div>
</template>

<script setup lang="ts">
import Navbar from './Navbar.vue';

</script>