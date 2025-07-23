<template>
  <AdminLayout>
     <div class="max-w-3xl mx-auto">
        <div class="flex justify-between px-6 py-4 bg-gradient-to-r from-purple-600 to-indigo-600">
            <h3 class="text-xl font-semibold text-white">Members table</h3>
            <router-link to="/app/createMember">
                <button type="submit" class="cursor-pointer text-sm font-bold text-indigo-500 bg-white px-2 py-1 rounded">Create Member</button>
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
                <input v-model="form.name" type="search" id="default-search" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search Members" required />
                <button type="submit" class="text-white absolute end-2.5 bottom-1.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-1 py-1 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                  {{ loading ? 'Searching' : 'search' }}
                </button>
            </div>
          </form>
          <table class="min-w-full divide-y divide-gray-400">
             <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                </tr>

             </thead>
             <tbody class="bg-white divide-y divide-gray-200">
                <tr 
                v-for="member in members"
                v-bind:key="member.id"
                class="hover:bg-gray-50 transition-colors duration-150">
                  <td class="flex gap-2 items-center px-6 py-4 whitespace-nowrap">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center text-white font-medium">
                        ME
                    </div>
                    <div class="text-sm font-medium text-gray-900">
                         {{ member.name }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    {{ member.email }}
                  </td>
                </tr>
             </tbody>
             <tfoot>
              <tr >
                <td colspan="6" class="px-6 py-4">
                  <div class="flex items-center justify-between">
                  <div class="text-sm text-gray-700">
                  Showing <span class="font-medium">{{ pagination.from || 0}}</span> to <span class="font-medium">{{ pagination.to || 0 }} </span>
                  </div>
                  <VueAwesomePaginate 
                   v-model="currentPage"
                   :total-items="pagination.total"
                   :items-per-page="pagination.per_page"
                   :max-pages-shown="5"
                   @click="handlePage"
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
import axios from 'axios';
import AdminLayout from '../AdminLayout.vue';
import { onMounted,ref,reactive } from 'vue';
import { VueAwesomePaginate } from 'vue-awesome-paginate';
import { showError, successMsg } from '../../../helpers/toast-notification';
const currentPage = ref(1);
const form = reactive({
    name:''
  })
const loading = ref(false);

const handlePage = (page) => {
  currentPage.value = page;
  console.log(page);
  list(page)
}
const members = ref([{}]);
const pagination = ref({
  current_page:1,
  from:0,
  to:0,
  total:0,
  per_page:3,
  last_page:0
})
onMounted(()=>{
  list()
  
})
const list = async(page = 1) =>{
 const response = await axios.get(`/api/members`,{
  params:{
    page:currentPage.value,
    per_page:pagination.value.per_page,  
  }
 })
 if(response.data.status){
  members.value = response.data.members.data;
  pagination.value = {
    current_page:response.data.members.current_page,
    from:response.data.members.from,
    to:response.data.members.to,
    total:response.data.members.total,
    per_page:response.data.members.per_page,
    last_page:response.data.members.last_page
  }
 }
 
 
};
const search = async() =>{
  try {
    
    const response = await axios.post('/api/search',{
      ...form,
      page:pagination.value.current_page,
      per_page:pagination.value.per_page
    });
    if(response.data.status){
      console.log(response.data.pagination);
      members.value = response.data.members;
      pagination.value = response.data.pagination;
      
    }    
    
  } catch (error) {
    if(error.response){
      if (error.response.status === 404) {
        // No results found
        members.value = [];
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
<style>
/* Custom pagination styles */

</style>