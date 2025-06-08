<template>
   

<div>
<div class="bg-gradient-to-r from-blue-500 to-purple-600 min-h-screen flex items-center justify-center px-4">

  <div class="bg-white shadow-xl rounded-lg p-8 w-full max-w-md">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Login</h2>

    <form class="space-y-6" @submit.prevent="login">
      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
        <input 
          type="email" 
          id="email" 
          name="email" 
          v-model="form.email"
          required 
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="you@example.com"
        />
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          v-model="form.password"
          required 
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="••••••••"
        />
      </div>

      <!-- Confirm Password -->
      

      <!-- Submit Button -->
      <button
            type="submit"
            :disabled="loading"
            class="mt-5 tracking-wide font-semibold bg-indigo-500 text-gray-100 w-full py-4 rounded-lg hover:bg-indigo-700 transition-all duration-300 ease-in-out flex items-center justify-center focus:shadow-outline focus:outline-none">
            <span v-if="loading" class="">
              <svg class="animate-spin h-5 w-5 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
               </svg>
               
            </span>  
            {{ loading ? 'Registering...':'Login'  }}
     </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
      Don't  have an account? 
      <router-link to="/app/register" class="text-blue-600 hover:underline font-medium">Register</router-link> 
    </p>
  </div>

</div>
</div>

</template>
<script setup>
import { ref,reactive } from 'vue';
import {showError,successMsg} from '../../helpers/toast-notification'
import axios from 'axios'
import {  useRouter } from 'vue-router';
const router = useRouter();
const loading = ref(false);
const form = reactive({
    email:'',
    password:'',
});
const login = async()=>{
    try {
        const response = await axios.post('/api/login',form);
        loading.value=true;
        if(response.data.status){
          localStorage.setItem('auth_token',response.data.token);
          axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
          localStorage.setItem('user',JSON.stringify(response.data.user));
          successMsg(response.data.message);
          router.push('/app/dashboard')
        }
        else{
            showError(response.data.message)
        }
    } catch (error) {
        if(error.response){
        showError(error.response.data.message)
      }
    }
    finally{
        loading.value=false;
    }
}
</script>