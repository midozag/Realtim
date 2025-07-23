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
            <div class="bg-white rounded-lg shadow-md p-6">
              <h2 class="text-lg font-medium text-gray-600 mb-4">Tasks</h2>
              <apexchart
                type="pie"
                :options="tasksChartOptions"
                :series="taskChartSeries"
                height="250"
              />
              <div class="flex justify-center space-x-4 mt-4">
                <div class="flex items-center">
                  <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                  <span class="text-sm text-gray-600">Pending</span>
                </div>
                <div class="flex items-center">
                  <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                  <span class="text-sm text-gray-600">Completed</span>
                </div>
        </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
              <h2 class="text-lg font-medium text-gray-600 mb-4">Task Progress</h2>
              <apexchart
                type="radialBar"
                :options="progressChartOptions"
                :series="progressChartSeries"
                height="250"
              />
            </div>
        </div>
       </div>   
   </AdminLayout>
</template>
<script setup>
import AdminLayout from './AdminLayout.vue';
import {ref,onMounted} from 'vue';
import axios from 'axios';
import { showError } from '../../helpers/toast-notification';
const totalProjects = ref(0);
const pendingTasks = ref(0);
const completedTasks = ref(0);
const taskProgress = ref(0);
const pinnedProject = ref();
const chartData = ref({tasks:[],progress:0});
onMounted(async()=>{
   
  
    getCount();
    pinnedProject.value = await getProjectPinned()
    getChartData(pinnedProject.value);
    const channel = window.Echo.channel('countProject');
    channel.listen('NewProjectCreated', (e) => {
       totalProjects.value = e.countProject;
     })
     .error((error) => {
       console.error('❌ Channel error:', error);
     });
     
   // Test connection status
   channel.subscribed(() => {
     console.log('✅ Successfully subscribed to countProject channel');
   });
})

const tasksChartOptions = ref({
  chart: {
    type: 'pie',
    toolbar: {
      show: false
    }
  },
  labels: ['Pending', 'Completed'],
  colors: ['#3B82F6', '#10B981'], // Blue and Green
  dataLabels: {
    enabled: true,
    formatter: function (val) {
      return Math.round(val) + '%'
    },
    style: {
      fontSize: '14px',
      fontWeight: 'bold'
    }
  },
  legend: {
    show: false // We'll create custom legend
  },
  plotOptions: {
    pie: {
      donut: {
        size: '0%'
      }
    }
  }
})
const taskChartSeries = ref([pendingTasks.value,completedTasks.value])
const progressChartOptions = ref({
  chart: {
    type: 'radialBar',
    toolbar: {
      show: false
    }
  },
  plotOptions: {
    radialBar: {
      startAngle: -90,
      endAngle: 90,
      hollow: {
        margin: 15,
        size: '70%'
      },
      dataLabels: {
        name: {
          show: false
        },
        value: {
          show: true,
          fontSize: '30px',
          fontWeight: 'bold',
          color: '#3B82F6',
          offsetY: 10,
          formatter: function (val) {
            return val + '%'
          }
        }
      }
    }
  },
  colors: ['#3B82F6'],
  fill: {
    type: 'gradient',
    gradient: {
      shade: 'light',
      type: 'horizontal',
      shadeIntensity: 0.5,
      gradientToColors: ['#06B6D4'],
      inverseColors: true,
      opacityFrom: 1,
      opacityTo: 1,
      stops: [0, 100]
    }
  },
  stroke: {
    dashArray: 4
  }
})
const progressChartSeries = ref([taskProgress.value])

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
const getProjectPinned = async() =>{
  try {
    const response = await axios.get('/api/getPinnedProject');
    if(response.data.status){
      return response.data.project
        
    }
  } catch (error) {
    showError(error.response.data.message)
  }
}
const getChartData = async(id) =>{
 
  const response = await axios.get(`/api/getChartData?projectId=${id}`);
  pendingTasks.value = response.data.tasks[0];
  completedTasks.value = response.data.tasks[1];
  taskChartSeries.value = [pendingTasks.value, completedTasks.value];
  taskProgress.value = response.data.progress;
  progressChartSeries.value = [taskProgress.value];
}
</script>