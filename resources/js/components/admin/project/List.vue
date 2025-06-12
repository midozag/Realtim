<template>
  <AdminLayout>
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between px-6 py-4 bg-gradient-to-r from-purple-600 to-indigo-600">
            <h3 class="text-xl font-semibold text-white">Projects table</h3>
            <router-link to="/app/createProject">
                <button type="submit" class="cursor-pointer text-sm font-bold text-indigo-500 bg-white px-2 py-1 rounded">Create Project</button>
            </router-link>
        </div>
        <div class="overflow-x-auto bg-white">
          <form  class="w-80 my-2 ml-2" @submit.prevent="search">   
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center  pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input v-model="form.name" type="search" id="default-search" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search Projects" required />
                <button type="submit" class="text-white absolute end-2.5 bottom-1.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-1 py-1 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                  Search
                </button>
            </div>
          </form>
          <table class="w-full divide-y divide-gray-400 ">
             <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Title
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Completion
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Edit
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pinned
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        View
                    </th>
                </tr>
             </thead>
             <tbody class="bg-white divide-y divide-gray-200 ">
                <tr 
                v-for="project in projects"
                v-bind:key="project.id"
                class="hover:bg-gray-50 transition-colors duration-150">
                  <td class="flex gap-2 items-center px-6 py-4 whitespace-nowrap">
                    
                    <div class="text-sm font-medium text-gray-900">
                        {{ project.id }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ project.name }}
                  </td>
                  
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ project.task_progress?.progress}}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    <router-link to="/app/createProject">
                      <button type="submit" class="cursor-pointer text-sm font-bold text-indigo-500 bg-white px-2 py-1 rounded border-1 border-indigo-500">Edit</button>
                    </router-link>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"> 
                     <form @submit.prevent="pinned(project.id)">
                        <button type="submit" class="cursor-pointer text-sm font-bold text-red-500 bg-white px-2 py-1 rounded border-1 border-red-500">Pinned</button>
                     </form>
                  </td>
                
                  
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    <router-link to="/app/createProject">
                      <button type="submit" class="cursor-pointer text-sm font-bold text-orange-400 bg-white px-2 py-1 rounded border-1 border-orange-400">View</button>
                    </router-link>
                  </td>
                </tr>
             </tbody>
             <tfoot>
                <tr>
                    <td colspan="6" class="px-4 py-4">
                      <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ pagination.from || 0}}</span> to <span class="font-medium">{{ pagination.to || 0 }} </span>
                        </div>
                        <VueAwesomePaginate 
                          v-model="current_page"
                          :total-items="pagination.total"
                          :items-per-page="pagination.per_page"
                          :max-pages-shown="5"
                          @click="handlepage"
                        />
                      </div>
                    </td>
                </tr>
             </tfoot>
          </table>
    </div>
    </div>
    
  </AdminLayout>
</template>
<script setup>

import AdminLayout from '../AdminLayout.vue';
import {ref,reactive,onMounted} from 'vue';
import { VueAwesomePaginate } from 'vue-awesome-paginate';
import axios from 'axios';
import { showError } from '../../../helpers/toast-notification';
const current_page=ref(1);
const pagination = ref({
    current_page:1,
    from:0,
    to:0,
    total:3,
    per_page:2,
    last_page:0
})
const form =reactive({
    name:''
})
  onMounted(() => {
    list()
  });
const projects = ref([{}]);
const handlepage = (page) =>{
  current_page.value = page;
  console.log(page);
  list(page)
}
const list = async() =>{
    try {
        const response = await axios.get('/api/projects',{
            params:{
              page:current_page.value,
              per_page:pagination.value.per_page
            }
        });
        if(response.data.status){
            projects.value=response.data.projects.data
            pagination.value={
                current_page:response.data.projects.current_page,
                from:response.data.projects.from,
                to:response.data.projects.to,
                total:response.data.projects.total,
                per_page:response.data.projects.per_page,
                last_page:response.data.projects.last_page
            }
        }
        
        
    } catch (error) {
        if(error.response){
            showError(error.response.data.message)
        }
    }
}
const search = async() =>{ 
    
    const $requestData = {
      ...form,
      params:{
        page:pagination.value.current_page,
        per_page:pagination.value.per_page
      }
    };
    
  try {   
    const response = await axios.post('/api/searchProjects',$requestData);
    if(response.data.status){
      console.log(response.data.projects);
      projects.value = response.data.projects;
      pagination.value = response.data.pagination;  
    }     
  } 
  catch (error) {
    if(error.response){
      if (error.response.status === 404) {
        // No results found
        projects.value = [];
        pagination.value = error.response.data.pagination || {
          total: 0,
          per_page: 10,
          current_page: 1,
          last_page: 0,
          from: 0,
          to: 0
        };
        showError(error.response.data.message);
      }
    } 
  }
  finally{
    loading.value = false;
  }
  
}

</script>