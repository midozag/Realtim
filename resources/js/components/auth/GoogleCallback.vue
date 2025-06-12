<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="text-center">
      <h2 class="text-xl">Processing Google login...</h2>
      <p class="mt-2 text-gray-600">Please wait, you will be redirected shortly.</p>
    </div>
  </div>
</template>
<script setup>
 import axios from 'axios';
 import {onMounted} from 'vue';
 import {useRouter,useRoute} from 'vue-router';
 const router = useRouter();
 const route = useRoute();
 onMounted(()=>{
    async function showUser() {
        const token = route.query.token;
        const userId = route.query.userId;
        console.log(userId);
        
        if(token){
            localStorage.setItem('auth_token',token);
            const user = await axios.get(`/api/getUser/${userId}`);
            localStorage.setItem('user',JSON.stringify(user.data));
            axios.defaults.headers.common['Authorization']=`Bearer ${token}`;
            router.push('/app')
        }
        else{
            router.push('register?error=Authentication failed')
        }
    }
    showUser()   
 })
</script>