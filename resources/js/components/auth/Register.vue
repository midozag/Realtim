<template>
   

<div>
<div class="bg-gradient-to-r from-blue-500 to-purple-600 min-h-screen flex items-center justify-center px-4">

  <div class="bg-white shadow-xl rounded-lg p-8 w-full max-w-md ">
    
    <div class="flex flex-col items-center mt-4">
              <a
                  href="http://localhost:8000/api/auth/google"
                  class="w-full max-w-xs font-bold shadow-sm rounded-lg py-3 bg-indigo-100 text-gray-800 flex items-center justify-center transition-all duration-300 ease-in-out focus:outline-none hover:shadow focus:shadow-sm focus:shadow-outline">
                  <div class="bg-white p-2 rounded-full">
                      <svg class="w-4" viewBox="0 0 533.5 544.3">
                          <path
                              d="M533.5 278.4c0-18.5-1.5-37.1-4.7-55.3H272.1v104.8h147c-6.1 33.8-25.7 63.7-54.4 82.7v68h87.7c51.5-47.4 81.1-117.4 81.1-200.2z"
                              fill="#4285f4" />
                          <path
                              d="M272.1 544.3c73.4 0 135.3-24.1 180.4-65.7l-87.7-68c-24.4 16.6-55.9 26-92.6 26-71 0-131.2-47.9-152.8-112.3H28.9v70.1c46.2 91.9 140.3 149.9 243.2 149.9z"
                              fill="#34a853" />
                          <path
                              d="M119.3 324.3c-11.4-33.8-11.4-70.4 0-104.2V150H28.9c-38.6 76.9-38.6 167.5 0 244.4l90.4-70.1z"
                              fill="#fbbc04" />
                          <path
                              d="M272.1 107.7c38.8-.6 76.3 14 104.4 40.8l77.7-77.7C405 24.6 339.7-.8 272.1 0 169.2 0 75.1 58 28.9 150l90.4 70.1c21.5-64.5 81.8-112.4 152.8-112.4z"
                              fill="#ea4335" />
                      </svg>
                  </div>
                  <span class="ml-4">
                      Sign Up with Google
                  </span>
              </a>

    </div>

    <div class="my-8 border-b text-center">
        <div
            class="leading-none px-2 inline-block text-sm text-gray-600 tracking-wide font-medium bg-white transform translate-y-1/2">
            Create Account
        </div>
    </div>
    

    <form class="space-y-6" @submit.prevent="register">
      <!-- Full Name -->
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
        <input 
          type="text" 
          id="name" 
          name="name" 
          v-model="form.name"
          required 
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="John Doe"
        />
      </div>

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
      <div>
        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
        <input 
          type="password" 
          id="confirm_password" 
          name="password_confirmation" 
          v-model="form.password_confirmation"
          required 
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="••••••••"
        />
      </div>

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
            {{ loading ? 'Registering...':'Register'  }}
     </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
      Already have an account? 
      <router-link to="/app/login" class="text-blue-600 hover:underline font-medium">Login</router-link>
    </p>
  </div>

</div>
</div>

</template>
<script setup>
import { ref,reactive } from 'vue';
import {showError,successMsg} from '../../helpers/toast-notification'
import axios from 'axios'
const loading = ref(false);

const form = reactive({
    name:'',
    email:'',
    password:'',
    password_confirmation:''
});
const register = async() =>{
    console.log(form);
    loading.value=true
    try {
       const response = await axios.post('/api/register',form);
       console.log(response);      
       if(response.data.status)
       {
         successMsg("Registration succesful check your email")
       }
    } catch (error) {
        if(error.response && error.response.data){
            if(error.response.data.errors){
                const errors = error.response.data.errors;
                showError(Object.values(errors).flat().join('\n'));
                
            }
            else if(error.response.data.message){
                showError(Object.values(errors).flat().join('\n')) ;
            }
            else{
                showError(Object.values(errors).flat().join('\n'));
            }
        }
        else{
            showError(Object.values(errors).flat().join('\n'));
        }
    }
    finally{
        loading.value = false;
    }
    
}
</script>