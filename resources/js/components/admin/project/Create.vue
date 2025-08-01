<template>
  <AdminLayout>
     <div class="p-6">
         <form action="" @submit.prevent="create">
             <div class="mb-4">
                 <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Name
                 </label>
                 <div class="relative">
                     
                     <input  class="appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 pl-10 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                         v-model="form.name"
                         id="name" 
                         type="text" 
                         placeholder="Project"
                         required>
                 </div>
             </div>
             <div class="mb-6">
                 <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                     Start date
                 </label>
                 <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                       <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                     <input  class="appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 pl-10 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                         v-model="form.startDate"
                         id="email" 
                         type="date" 
                         required>
                 </div>
             </div>
             <div class="mb-6">
                 <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                     End date
                 </label>
                 <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                       <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div> 
                    <input  class="appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 pl-10 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                         v-model="form.endDate"
                         id="email" 
                         type="date"
                         required>
                 </div>
             </div>
             <button :disabled="loading" type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:bg-gradient-to-r hover:from-purple-600 hover:to-blue-500 font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline   hover:scale-105 transition-transform duration-200 ease-in-out">
                 <span v-if="loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <svg class="animate-spin h-5 w-5 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>  
                     {{ loading ? 'Creating...':'Create Project'  }}
             </button>     
         </form>
     </div>
  </AdminLayout>


</template>
<script setup>
import AdminLayout from '../AdminLayout.vue';

import {ref,reactive} from 'vue';
import axios from 'axios';
import { showError, successMsg } from '../../../helpers/toast-notification';
const loading = ref(false);
const form = reactive({
    name:'',
    startDate:'',
    endDAte:''
})
const create = async() =>{
  try {
    loading.value=true;
    const response = await axios.post('/api/createProject',form)
    if(response.data.status){
        successMsg(response.data.message)
    }
  } catch (error) {
    if(error.response){
            showError(error.response.data.message)
        }
  }
  finally{
    loading.value=false
  }
}

</script>