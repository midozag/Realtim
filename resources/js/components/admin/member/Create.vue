<template>
   <AdminLayout>
      <div class="p-6">
        <form action="" @submit.prevent="create">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Full Name
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input  class="appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 pl-10 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                        v-model="form.name"
                        id="name" 
                        type="text" 
                        placeholder="John Doe"
                        required>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email Address
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input class="appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 pl-10 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                        v-model="form.email"
                        id="email" 
                        type="email" 
                        placeholder="johndoe@example.com"
                        required>
                </div>
            </div>
            <button :disabled="loading" type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transform transition hover:scale-105 duration-200 ease-in-out">
                <span v-if="loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
                   <svg class="animate-spin h-5 w-5 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                     <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                     <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                   </svg>
                  </span>  
                    {{ loading ? 'Creating...':'Create Member'  }}
            </button>
        </form>
      </div>
   </AdminLayout>
</template>
<script setup>
import axios from 'axios';
import AdminLayout from '../AdminLayout.vue';
import { ref,reactive } from 'vue';
import { showError, successMsg } from '../../../helpers/toast-notification';
   
const loading= ref(false);
const form = reactive({
    name:'',
    email:''
});
const create = async() =>{
      loading.value=true;
      try {
        const response = await axios.post('/api/createMember',form);
        if(response.data.status){
          successMsg(response.data.message);

        }
      } catch (error) {
        if(error.response){
            showError(error.response.data.message)
        }
      }
      finally{
        loading.value = false;
      }
      
   }
</script>