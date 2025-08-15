<template>
   <AdminLayout>
    
    <div class="max-w-4xl mx-auto ">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <nav class="mb-4 sm:mb-6">
            <ol class="flex flex-wrap items-center space-x-1 sm:space-x-2 text-xs sm:text-sm text-gray-600">
                <li>
                    <router-link to="/app/createProject" class="text-blue-600 hover:text-blue-800 transition-colors break-words">
                        Create Project
                    </router-link>
                </li>
                <li class="flex items-center">
                    <span class="mx-1 sm:mx-2 text-gray-400">/</span>
                    <a href="#" class="text-blue-600 hover:text-blue-800 transition-colors break-words">
                        Projects
                    </a>
                </li>
            </ol>
        </nav>

        <!-- Project Title -->
        <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold text-gray-900 mb-3 sm:mb-4 break-words leading-tight">
            Project : {{ project.name }}
        </h1>

        <!-- Project Dates -->
        <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-8 mb-4 sm:mb-6 text-xs sm:text-sm text-gray-600">
            <div class="flex items-center">
                <span class="font-medium whitespace-nowrap">StartDate :</span>
                <span class="ml-2">{{ project.startDate }}</span>
            </div>
            <div class="flex items-center">
                <span class="font-medium whitespace-nowrap">EndDate :</span>
                <span class="ml-2">{{ project.endDate }}</span>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="w-full">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs sm:text-sm font-medium text-gray-700">Progress</span>
                <span class="text-xs sm:text-sm font-medium text-green-600">{{ progress}}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 sm:h-3">
                <div class="bg-green-500 h-2 sm:h-3 rounded-full transition-all duration-300 ease-out" :style="`width: ${progress}%`"></div>
            </div>
        </div>
      </div>
      <div class="overflow-x-auto pb-4">
       <div class="flex flex-col sm:flex-row sm:justify-center gap-3 sm:gap-4 lg:gap-6 my-2 sm:my-4 px-2 sm:px-4 overflow-x-auto">
        
    <!-- Add Task Column -->
    <div 
        @dragover="handleDragOverCompleted"
        @drop="handleDropStart"
        class="bg-gray-300 rounded-lg p-2 sm:p-3 lg:p-4 w-full sm:w-52 md:w-64 lg:w-72 flex-shrink-0">
        <!-- Add Task Button -->
        <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" 
            class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold py-2 px-3 rounded-lg mb-2 sm:mb-3 transition-colors text-xs sm:text-sm" 
            type="button">
            Add task
        </button>
        
        <!-- Modal (unchanged) -->
        <div id="crud-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <!-- ... modal content stays the same ... -->
        </div>
        
        <div v-if="!loading">
            <div v-for="task in tasks" v-bind:key="task.id">
                <form>
                    <div  
                        :draggable="true"
                        @dragstart="handleDragStart($event,task)"
                        @dragend="handleDragEnd"
                        class="bg-white rounded-lg p-2 sm:p-3 mb-2 sm:mb-3 shadow-sm border border-gray-200 relative cursor-move hover:shadow-md transition-shadow">
                        <button @click.prevent="Delete(task.id)" type="button" 
                            class="absolute top-1 right-1 text-gray-400 hover:text-gray-600 font-bold text-sm">
                            ×
                        </button>
                        <h3 class="font-medium text-gray-800 mb-1 sm:mb-2 pr-4 text-xs sm:text-sm">{{ task.name }}</h3>
                        <div class="flex items-center">
                            <div class="flex -space-x-1">
                                <div
                                    v-for="(taskmember,index) in task.taskmembers"
                                    v-bind:key="taskmember.id"
                                    class="w-5 h-5 sm:w-6 sm:h-6 rounded-full flex items-center justify-center text-white text-[10px] sm:text-xs font-medium border border-white"
                                    :class="getAvatarColor(index)">
                                    {{ taskmember.member.name[0] }}
                                </div>
                            </div>
                            <div class="text-[10px] sm:text-xs text-gray-600 ml-2">{{ task.taskmembers?.length }} Membres</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div v-else></div>
    </div>

    <!-- Pending Column -->
    <div 
        @dragover="handleDragOver"
        @drop="handleDrop"
        class="bg-gray-300 rounded-lg p-2 sm:p-3 lg:p-4 w-full sm:w-52 md:w-64 lg:w-72 flex-shrink-0">
        <!-- Column Header -->
        <div class="bg-gray-400 text-white font-semibold py-2 px-3 rounded-lg mb-2 sm:mb-3 text-center text-xs sm:text-sm">
            Pending
        </div>
        
        <div v-for="task in pendingTasks" v-bind:key="task.id">
            <form>
                <div  
                    :draggable="true"
                    @dragstart="handleDragStart($event,task)"
                    @dragend="handleDragEnd"
                    class="bg-white rounded-lg p-2 sm:p-3 mb-2 sm:mb-3 shadow-sm border border-gray-200 relative cursor-move hover:shadow-md transition-shadow">
                    <h3 class="font-medium text-gray-800 mb-1 sm:mb-2 pr-4 text-xs sm:text-sm">{{ task.name }}</h3>
                    <div class="flex items-center">
                        <div class="flex -space-x-1">
                            <div
                                v-for="(taskmember,index) in task.taskmembers"
                                v-bind:key="taskmember.id"
                                class="w-5 h-5 sm:w-6 sm:h-6 rounded-full flex items-center justify-center text-white text-[10px] sm:text-xs font-medium border border-white"
                                :class="getAvatarColor(index)">
                                {{ taskmember.member.name[0] }}
                            </div>
                        </div>
                        <div class="text-[10px] sm:text-xs text-gray-600 ml-2">{{ task.taskmembers?.length }} Membres</div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Completed Column -->
    <div 
        @dragover="handleDragOverCompleted"
        @drop="handleDropCompleted"
        class="bg-gray-300 rounded-lg p-2 sm:p-3 lg:p-4 w-full sm:w-52 md:w-64 lg:w-72 flex-shrink-0">
        <!-- Column Header -->
        <div class="bg-gray-400 text-white font-semibold py-2 px-3 rounded-lg mb-2 sm:mb-3 text-center border-2 border-dashed border-gray-500 text-xs sm:text-sm">
            Completed
        </div>
        
        <div v-for="task in completedTasks" v-bind:key="task.id">
            <form>
                <div  
                    :draggable="true"
                    @dragstart="handleDragStart($event,task)"
                    @dragend="handleDragEnd"
                    class="bg-white rounded-lg p-2 sm:p-3 mb-2 sm:mb-3 shadow-sm border border-gray-200 relative cursor-move hover:shadow-md transition-shadow">
                    <h3 class="font-medium text-gray-800 mb-1 sm:mb-2 pr-4 text-xs sm:text-sm">{{ task.name }}</h3>
                    <div class="flex items-center">
                        <div class="flex -space-x-1">
                            <div
                                v-for="(taskmember,index) in task.taskmembers"
                                v-bind:key="taskmember.id"
                                class="w-5 h-5 sm:w-6 sm:h-6 rounded-full flex items-center justify-center text-white text-[10px] sm:text-xs font-medium border border-white"
                                :class="getAvatarColor(index)">
                                {{ taskmember.member.name[0] }}
                            </div>
                        </div>
                        <div class="text-[10px] sm:text-xs text-gray-600 ml-2">{{ task.taskmembers.length }} Membres</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
    </div>
    </div>
   
   </AdminLayout>
</template>

<script setup>
import axios from 'axios';
import { onMounted,reactive,ref } from 'vue';
import { initFlowbite } from 'flowbite'
import { useRoute } from 'vue-router';
import AdminLayout from '../AdminLayout.vue';
import { showError, successMsg } from '../../../helpers/toast-notification';
const route = useRoute();
const slug = route.query.slug;
const loading = ref(false);
const project = ref({});
const tasks = ref([{}]);
const members = ref([{}]);
const draggedTask = ref(null);
const pendingTasks = ref([]);
const completedTasks = ref([]);
const allTasks = ref([]);
const progress = ref(0)
const form = reactive({
    name:''
});
const taskObject = reactive({
    name:'',
    projectId:'',
    memberIds:[]
});
let selectedMembers = ref([]);

onMounted(()=>{
  ListTask()
  ListPendingTask()
  ListCompletedTasks()
  ListAllTask()
   
  getProject()
  initFlowbite()
  getMembers()
  
  
  
})
const calculateProgress = async() =>{
    if (allTasks.value.length > 0) {
        progress.value = Math.round((completedTasks.value.length / allTasks.value.length)*100)
        const projectId = await getProject();
        console.log(projectId);
        
        try {
            const response = await axios.put('/api/updateProgress',{
            progress:progress.value,
            id:projectId
            });
            if(response.data.status){
                successMsg(response.data.message)
            }
        } catch (error) {
            if(error.response){
                showError(error.response.data.message)
            }
        }
    } else {
        progress.value = 0
    }
}
const getProject = async() =>{
    try {
        const response = await axios.get(`/api/getProjectBySlug?slug=${slug}`);
        if(response.data.status){
            project.value= response.data.projects;
            
            return project.value.id;  
                       
        }
    } catch (error) {
        if(error.response){
            showError(error.response.data.message);    
        }
    }
}
const ListAllTask = async() =>{
  const id = await getProject();
  try {
    const response = await axios.get(`/api/getAllTasks?projectId=${id}`);
    if(response.data.status){
        allTasks.value = response.data.tasks;
        calculateProgress()
        loading.value=true
    }
  } catch (error) {
    if(error.response){
        showError(error.response.data.message);      
    }
  }   
}
const ListTask = async() =>{
  const id = await getProject();
  try {
    const response = await axios.get(`/api/getTasks?projectId=${id}`);
    if(response.data.status){
        tasks.value = response.data.tasks;
        calculateProgress()
        loading.value=true
           
    }
  } catch (error) {
    if(error.response){
        showError(error.response.data.message);      
    }
  }   
}
const ListPendingTask = async() =>{
  const id = await getProject();
  try {
    const response = await axios.get(`/api/getPendingTasks?projectId=${id}`);
    if(response.data.status){
        pendingTasks.value = response.data.tasks;
        return pendingTasks.value      
    }
  } catch (error) {
    if(error.response){
        showError(error.response.data.message);      
    }
  }   
}
const ListCompletedTasks = async() =>{
   const id = await getProject();
   try {
    const response = await axios.get(`/api/getCompletedTasks?projectId=${id}`);
    if(response.data.status){
        completedTasks.value = response.data.tasks;
    }
   } catch (error) {
    if(error.response){
        showError(error.response.data.message);      
    }
   } 
}
const getAvatarColor = (index) => {
    const colors = [
        'bg-blue-500',    // First member
        'bg-gray-500',    // Second member  
        'bg-green-500',   // Third member
        'bg-purple-500',  // Fourth member
        'bg-red-500',     // Fifth member
        'bg-yellow-500',  // Sixth member
    ]
    return colors[index % colors.length] // Cycle through colors
}
const getMembers = async() =>{
    try {
        const response = await axios.get('/api/allmembers');
        if(response.data.status){
            members.value = response.data.members;        
        }
    } 
    catch (error) {
        if(error.response){
        showError(error.response.data.message);
        }
    }

}
const addMember = async(id,name)=>{
   if(selectedMembers.value.some(member=>member.id === id)){
    
    return
   }
    
    selectedMembers.value.push({id,name})
    
    taskObject.memberIds = selectedMembers.value.map(member => member.id)
    console.log('Updated memberIds:', taskObject.memberIds)
    const memberTag = document.createElement('span');
    memberTag.className = 'inline-flex items-center bg-gray-100 text-gray-800 text-sm font-medium px-3 py-1 rounded-full';
    memberTag.dataset.id = id;

    memberTag.innerHTML = `
        ${name} 
        <button 
            class="ml-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full w-4 h-4 flex items-center justify-center text-xs transition-colors remove-btn" 
            title="Remove ${name}" onclick="removeMember(${id})"
            type="button"
        >
            ×
        </button>
    `;
    document.getElementById('selectedMembers').appendChild(memberTag);
    
}
window.removeMember = (id) => {
  selectedMembers.value = selectedMembers.value.filter(member => member.id !== id)
  console.log(selectedMembers.value);
  const memberTag = document.querySelector(`[data-id="${id}"]`);
  if(memberTag){
    memberTag.remove()
  }
}
const search = async() =>{
    
    try {
        const response = await axios.post('/api/searchMember',{...form});
        console.log(response);
        
        if(response.data.status){
            members.value = response.data.members;
        }
    } catch (error) {
        if(error.response){
            if(error.response.status === 404){
                members.value=[];
                showError(error.response.data.message)
            }
        }
    }
}
const AddTask = async() =>{
    taskObject.projectId = await getProject();
    
    try {
        const response = await axios.post('/api/createtask',{...taskObject});
        const addedTask = await ListTask();
        const secaddedTask = await ListAllTask()
        if(response.data.status){
          successMsg(response.data.message);
          loading.value.preventDefault
          tasks.value.unshift()
          allTasks.value.unshift()
          calculateProgress()     
        }
    } catch (error) {
       if(error.response){
            if(error.response.status === 422){
                showError(error.response.data.message)
                loading.value.false
            }
        } 
    }   
}
const Delete = async(id)=>{    
 try {
    const response = await axios.post('/api/deleteTask',{id:id});
    console.log(response.data.message);
    if(response.data.status){
    successMsg(response.data.message)
    tasks.value = tasks.value.filter(task => task.id !== id);
    allTasks.value = allTasks.value.filter(task => task.id !== id);
    calculateProgress()
  }
   } 
   catch (error) {
    if(error.response){
            if(error.response.status === 422){
                showError(error.response.data.message)
            }
        } 
   }
}
const handleDragStart = (event,task) =>{
    draggedTask.value = task;
    event.dataTransfer.effectAllowed = 'move';
    event.target.style.opacity = '0.5'
}
const handleDragEnd = (event) =>{
    event.target.style.opacity = '1'
}
const handleDragOver = (event) =>{
    event.preventDefault();
    event.dataTransfer.dropEffect = 'move';
}
const handleDragOverCompleted = (event) =>{
    event.preventDefault();
    event.dataTransfer.dropEffect = 'move';
    
    
}
const handleDropStart = async(event) =>{
  event.preventDefault();
  if(draggedTask){   
    try {
        const response = await axios.post('/api/tasks/completedtonotstarted',{taskid:draggedTask.value.id});
        const StartedTask = await ListTask()
        if(response.data.status){
            successMsg(response.data.message)
            pendingTasks.value = pendingTasks.value.filter(task => task.id !== draggedTask.value.id)
            completedTasks.value = completedTasks.value.filter(task => task.id !== draggedTask.value.id
            )
            calculateProgress()
            tasks.value.unshift();              
        }
    } catch (error) {
        if(error.response){
            if(error.response.status === 422){
                showError(error.response.message)
            }
        }
    } 
  }
}
const handleDrop = async(event) =>{
  event.preventDefault();
  if(draggedTask){   
    try {
        const response = await axios.post('/api/tasks/notstartedtopending',{taskid:draggedTask.value.id});
        const pendingTask = await ListPendingTask()
        if(response.data.status){
            successMsg(response.data.message)
            tasks.value = tasks.value.filter(task => task.id !== draggedTask.value.id)
            completedTasks.value = completedTasks.value.filter(task => task.id !== draggedTask.value.id)
            calculateProgress()
            pendingTasks.value.unshift();              
        }
    } catch (error) {
        if(error.response){
            if(error.response.status === 422){
                showError(error.response.message)
            }
        }
    } 
  }
}
const handleDropCompleted = async(event) =>{
  event.preventDefault();
  if(draggedTask){
    try {
        const response = await axios.post('/api/tasks/pendingtocompleted',{taskid:draggedTask.value.id});
        const completedTask = await ListCompletedTasks();
        if(response.data.status){
            pendingTasks.value = pendingTasks.value.filter(task => task.id !== draggedTask.value.id)
            tasks.value = tasks.value.filter(task => task.id !== draggedTask.value.id)
            successMsg(response.data.message)
            calculateProgress()
            completedTasks.value.unshift();
        }
    } 
    catch (error) {
       if(error.response){
            if(error.response.status === 422){
                showError(error.response.message)
            }
        }    
    }
    
  }

}

</script>