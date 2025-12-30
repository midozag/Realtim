const setupRealTimeListeners = () => {
    console.log('🔧 setupRealTimeListeners called!');
    console.log('📌 Pinned project:', pinnedProject.value);

    const channel = window.Echo.channel('countProject');
    channel.listen('NewProjectCreated', (e) => {
      totalProjects.value = e.countProject;
    });

    if(pinnedProject.value) {
      const projectChannel =
  window.Echo.channel(`project.${pinnedProject.value}`);

      projectChannel.listenToAll((eventName, data) => {
        console.log('📊 Dashboard event:', eventName, data);

        if (eventName === '.TaskStatusUpdated' || eventName ===      
  'TaskStatusUpdated') {
          console.log('✅ Updating charts...');
          getChartData(pinnedProject.value);
        }

        if (eventName === '.TaskCreated' || eventName ===
  'TaskCreated') {
          console.log('✅ New task - updating charts...');
          getChartData(pinnedProject.value);
        }

        if (eventName === '.TaskDeleted' || eventName ===
  'TaskDeleted') {
          console.log('✅ Task deleted - updating charts...');       
          getChartData(pinnedProject.value);
        }
      });

      taskStatusChannel.value = projectChannel;
    }
  }