import { createRouter,createWebHistory } from "vue-router";
import Register from '../components/auth/Register.vue';
import Login from '../components/auth/Login.vue'
import Dashboard from '../components/admin/Dashboard.vue'
import GoogleCallback from '../components/auth/GoogleCallback.vue'
import CreateProject from '../components/admin/project/Create.vue'
import ListProject from '../components/admin/project/List.vue'

const routes = [
    {
        path:'/app',
        redirect:'/app/dashboard'
    },
    {
        path:'/app/dashboard',
        name:'dashboard',
        component:Dashboard,
        meta:{requiresAuth:true}
    },
    {
        path:'/app/createProject',
        name:'CreateProject',
        component:CreateProject,
        meta:{requiresAuth:true}
    },
    {
        path:'/app/projects',
        name:'projects',
        component:ListProject,
        meta:{requiresAuth:true}
    },
    {
        path:'/app/register',
        name:'register',
        component:Register,
        meta:{guestOnly:true}
    },
    {
        path:'/app/login',
        name:'login',
        component:Login,
        meta:{guestOnly:true}
    },
    {
        path:'/app/google',
        name:'google',
        component:GoogleCallback,
        meta:{guestOnly:true}
    }
]

const router = createRouter({
    history:createWebHistory(),
    routes
});
router.beforeEach((to, from, next)=>{
    const isAuthenticated = localStorage.getItem('auth_token');

    if(to.meta.requiresAuth && !isAuthenticated){
        next('/app/register');
    }
    else if(to.meta.guestOnly && isAuthenticated){
        next('/app/dashboard')
    }
    else{
        next()
    }
});
export default router;
