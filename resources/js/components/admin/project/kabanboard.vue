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
       <div class="flex gap-3 sm:gap-4 lg:gap-6 my-2 sm:my-4 min-w-max">
        
        <!-- Add Task Column -->
        <div 
        @dragover="handleDragOverCompleted"
        @drop="handleDropStart"
        class="bg-gray-300 rounded-lg p-3 sm:p-4 w-60 sm:w-70 lg:w-80 min-w-64 sm:min-w-70 lg:min-w-80">
            <!-- Add Task Button -->
            <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold py-2 sm:py-3 px-3 sm:px-4 rounded-lg mb-3 sm:mb-4 transition-colors text-sm sm:text-base" type="button">
              Add task
            </button>
            <div id="crud-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
               <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Add task
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="" @submit.prevent="AddTask">
                <div class="p-6">
                <div class="mb-4" id="selectedMembers">
                    <!-- Selected member tags will appear here -->
                </div>
                <!-- Task Name Input -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Task name</label>
                    <input 
                        v-model="taskObject.name"
                        type="text" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter task name" required>
                </div>
        
        <!-- Search Member Input -->
                <form  class="w-full my-2 " @submit.prevent="search">   
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center  pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input v-model="form.name" type="search" id="default-search" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search Members" />
                        <button type="submit" class="text-white absolute end-2.5 bottom-1 bg-gradient-to-r from-purple-600 to-indigo-600 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-1 py-1 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                         search
                        </button>
                    </div>
                </form>
        
        <!-- Members Table -->
                <div class="border border-gray-200 rounded-md overflow-hidden">
            <!-- Table Header -->
                <div class="grid grid-cols-3 bg-gray-50 border-b border-gray-200 text-sm font-medium text-gray-700">
                    <div class="px-4 py-3">ID</div>
                    <div class="px-4 py-3">Name</div>
                    <div class="px-4 py-3">Select</div>
                </div>
            
            <!-- Table Rows -->
                <div
                v-for="member in members"
                v-bind:key="member.id"
                class="divide-y divide-gray-200">
                    <!-- Row 1 -->
                    <div class="grid grid-cols-3 hover:bg-gray-50 transition-colors">
                        <div class="px-4 py-3 text-sm text-gray-900">#{{ member.id }}</div>
                        <div class="px-4 py-3 text-sm text-gray-900">{{ member.name }}</div>
                        <div class="px-4 py-3">
                            <button
                            @click.prevent="addMember(member.id,member.name )"
                                class="add-btn text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                                + Add
                            </button>
                        </div>
                    </div>
                
                </div>
                </div>
                </div>
            <!--Modal footer-->
            <div class="flex justify-end p-6 border-t border-gray-200">
               <button type="submit" data-modal-toggle="crud-modal" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                Add Task
               </button>
            </div>
            </form>

        </div>
        </div>
            </div> 
            
            <div v-if="loading">
                <div  
             v-for="task in tasks"
             v-bind:key="task.id"
            >
              <form >
                <div  
                :draggable="true"
                @dragstart="handleDragStart($event,task)"
                @dragend="handleDragEnd"
                class="bg-white rounded-lg p-3 sm:p-4 mb-3 sm:mb-4 shadow-sm border border-gray-200 relative">
                <button @click.prevent="Delete(task.id)" type="button" class="absolute top-2 sm:top-3 right-2 sm:right-3 text-gray-400 hover:text-gray-600 font-bold text-lg">
                    ×
                </button>
                <h3 class="font-medium text-gray-800 mb-2 sm:mb-3 pr-6 text-sm sm:text-base">{{ task.name }} </h3>
                <div  class="flex items-center">
                    <div
                    v-for="(taskmember,index) in task.taskmembers"
                    v-bind:key="taskmember.id"
                    class="flex ">
                        <div class="w-6 h-6 sm:w-8 sm:h-8  rounded-full flex items-center justify-center text-white text-xs sm:text-sm font-medium border-2 border-white"
                         :class="getAvatarColor(index)"
                        >
                            {{ taskmember.member.name[0] }}
                        </div>
                        
                    </div>
                    <div  class="text-sm sm:text-sm text-gray-600 ml-2">{{ task.taskmembers?.length }} Membres</div>
                </div>
              </div>
              </form>
            </div>
            </div>
            <div v-else>

            </div>
            
           
            
        </div>

        <!-- Pending Column -->
        <div 
        
        @dragover="handleDragOver"
        @drop="handleDrop"
        class="bg-gray-300 rounded-lg p-3 sm:p-4 w-64 sm:w-70 lg:w-80 min-w-64 sm:min-w-70 lg:min-w-80">
            <!-- Column Header -->
            <div class="bg-gray-400 text-white font-semibold py-2 sm:py-3 px-3 sm:px-4 rounded-lg mb-3 sm:mb-4 text-center text-sm sm:text-base">
                Pending
            </div>
            
            <div  
             v-for="task in pendingTasks"
             v-bind:key="task.id"
            >
              <form >
                <div  
                :draggable="true"
                @dragstart="handleDragStart($event,task)"
                @dragend="handleDragEnd"
                class="bg-white rounded-lg p-3 sm:p-4 mb-3 sm:mb-4 shadow-sm border border-gray-200 relative">
                
                <h3 class="font-medium text-gray-800 mb-2 sm:mb-3 pr-6 text-sm sm:text-base">{{ task.name }} </h3>
                <div  class="flex items-center">
                    <div
                    v-for="(taskmember,index) in task.taskmembers"
                    v-bind:key="taskmember.id"
                    class="flex ">
                        <div class="w-6 h-6 sm:w-8 sm:h-8  rounded-full flex items-center justify-center text-white text-xs sm:text-sm font-medium border-2 border-white"
                         :class="getAvatarColor(index)"
                        >
                            {{ taskmember.member.name[0] }}
                        </div>
                        
                    </div>
                    <div  class="text-sm sm:text-sm text-gray-600 ml-2">{{ task.taskmembers?.length }} Membres</div>
                </div>
              </div>
              </form>
            </div>
            
            
            <!-- Task Card -->
            
        </div>

        <!-- Completed Column -->
        <div 
        @dragover="handleDragOverCompleted"
        @drop="handleDropCompleted"
        class="bg-gray-300 rounded-lg p-3 sm:p-4 w-64 sm:w-70 lg:w-80 min-w-64 sm:min-w-70 lg:min-w-80">
            <!-- Column Header -->
            <div class="bg-gray-400 text-white font-semibold py-2 sm:py-3 px-3 sm:px-4 rounded-lg mb-3 sm:mb-4 text-center border-2 border-dashed border-gray-500 text-sm sm:text-base">
                Completed
            </div>
            <div  
             v-for="task in completedTasks"
             v-bind:key="task.id"
            >
              <form >
                <div  
                :draggable="true"
                @dragstart="handleDragStart($event,task)"
                @dragend="handleDragEnd"
                class="bg-white rounded-lg p-3 sm:p-4 mb-3 sm:mb-4 shadow-sm border border-gray-200 relative">
                
                <h3 class="font-medium text-gray-800 mb-2 sm:mb-3 pr-6 text-sm sm:text-base">{{ task.name }} </h3>
                <div  class="flex items-center">
                    <div
                    v-for="(taskmember,index) in task.taskmembers"
                    v-bind:key="taskmember.id"
                    class="flex ">
                        <div class="w-6 h-6 sm:w-8 sm:h-8  rounded-full flex items-center justify-center text-white text-xs sm:text-sm font-medium border-2 border-white"
                         :class="getAvatarColor(index)"
                        >
                            {{ taskmember.member.name[0] }}
                        </div>
                        
                    </div>
                    <div  class="text-sm sm:text-sm text-gray-600 ml-2">{{ task.taskmembers.length }} Membres</div>
                </div>
              </div>
              </form>
            </div>

            <!-- Task Card 1 -->
            

            
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