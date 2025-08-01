<template>
    <nav>
     <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
           <div class="flex">
            <!--Logo-->
             <div class="flex flex-shrink-0 items-center">
              <router-link to="/app/dashboard" class="text-xl font-bold text-indigo-600">
                Realtime Task
              </router-link>
             </div>
             <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <router-link 
                v-for="item in navItems"
                :key="item.path"
                :to="item.path"
                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                :class="[$route.path === item.path ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ]"
              >
                   {{ item.name }}
              </router-link> 
            </div> 
           </div>
           <div class="hidden sm:ml-6 sm:flex sm:items-center">
            <div class="ml-3 relative">
             <div>
    <button 
        @click="isProfileMenuOpen = !isProfileMenuOpen" 
        class="bg-indigo-500 hover:bg-indigo-600 text-white rounded-full flex items-center justify-center text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-8 h-8 sm:w-10 sm:h-10 transition-colors duration-200">
        {{ user.name?.[0]?.toUpperCase() }}
    </button>
</div>
             <div
             v-if="isProfileMenuOpen"
             class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
             
             <router-link to="/app/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                Your Profile
              </router-link>
              <router-link to="/app/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                Settings
              </router-link>
              <button @click="logout" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                Sign out
              </button>
             </div>
            </div>
           </div>
           <div class="flex items-center sm:hidden">
            <button
            @click="isMobileMenuOpen = !isMobileMenuOpen"
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
            >
            <span class="sr-only">Open main menu</span>
            <!-- Icon when menu is closed -->
            <svg 
              v-if="!isMobileMenuOpen" 
              class="block h-6 w-6" 
              xmlns="http://www.w3.org/2000/svg" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor" 
              aria-hidden="true"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <!-- Icon when menu is open -->
            <svg 
              v-else 
              class="block h-6 w-6" 
              xmlns="http://www.w3.org/2000/svg" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor" 
              aria-hidden="true"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            </button>
           </div>
           
        </div>
        </div>
        <div
        v-if="isMobileMenuOpen" class="sm:hidden"
        >
        <div class="pt-2 pb-3 space-y-1">
            <router-link 
              v-for="item in navItems"
              :key="item.path"
              :to="item.path"
              class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
              :class="[$route.path === item.path ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800']"
            >
              {{ item.name }}
            </router-link>
        </div>

        </div>
        <div class="pt-4 pb-3 border-t border-gray-200 sm:hidden">
        <div class="flex items-center px-4">
          <div class="flex-shrink-0">
            <div class="h-10 w-10 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-600">
              user
            </div>
          </div>
          <div class="ml-3">
            <div class="text-base font-medium text-gray-800">{{ user.name }} </div>
            <div class="text-sm font-medium text-gray-500"> {{ user.email }} </div>
          </div>
        </div>
        <div class="mt-3 space-y-1 sm:hidden">
          <router-link to="/app/profile" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
            Your Profile
          </router-link>
          <router-link to="/app/settings" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
            Settings
          </router-link>
          <button @click="logout" class="w-full text-left block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
            Sign out
          </button>
        </div>
      </div>
     </div>

    </nav>
</template>
<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import { successMsg } from "../../helpers/toast-notification";
import router from '../../router';
const user = ref({});
const isProfileMenuOpen = ref(false);
const isMobileMenuOpen = ref(false);
onMounted(()=>{
    const storedUser = localStorage.getItem('user');
    if(storedUser){
        let cleanData = storedUser.replace(/&quot;/g,'"');
        user.value=JSON.parse(cleanData)
    }
    
    });

const logout = async() =>{
  try {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');
    delete axios.defaults.headers.common['Authorization'];
    successMsg('Logout succesfully');
    router.push('/app/login')
  } catch (error) {
    console.error('Logout error:',error);
    showError('Error logout')
  }
}
const navItems = [
    {name:'Dashboard',path:'/app/dashboard'},
    {name:'Projects',path:'/app/projects'},
    {name:'Members',path:'/app/members'},
]
</script>