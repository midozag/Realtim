<template>
   <AdminLayout>
       <div class="project-dashboard p-6 bg-gray-50 min-h-screen">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Project :</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center">
              <h2 class="text-lg font-medium text-gray-600 mb-4">Total Projects</h2>
              <div class="text-5xl font-bold text-gray-800">
                {{ totalProjects }}
              </div>
            </div>
        </div>
       </div>   
   </AdminLayout>
</template>
<script setup>
import AdminLayout from './AdminLayout.vue';
import {ref,onMounted} from 'vue';
onMounted(()=>{
   getCount()
})
const totalProjects = ref(0);

const getCount = async() =>{
   try {
    const response = await axios.get('/api/count');
    
    totalProjects.value=response.data.count
            
    
   } catch (error) {
    if(error.response){
        showError(error.response.data.message)
    }
   } 
}
</script>