<script setup>
import { RouterView, useRoute } from 'vue-router'
import { watch, onMounted } from 'vue'
import { useJobStore } from './stores/job'
import Header from './components/reusable/Header.vue'
import Footer from './components/reusable/Footer.vue'
import Cookie from './scripts/Cookie'

const route = useRoute()
const jobStore = useJobStore()

// Initialize user profile if authenticated
onMounted(async () => {
  const token = Cookie.getCookie('job-app')
  
  if (token) {
    console.log('App mounted with auth token, initializing profile')
    
    // Try to restore user from localStorage
    const savedUser = localStorage.getItem('job-user')
    if (savedUser) {
      jobStore.user = JSON.parse(savedUser)
      
      // Initialize profile data from the API
      await jobStore.initializeProfile()
    }
  }
})

// Watch for route changes to ensure proper scroll behavior
watch(
  () => route.path,
  () => {
    // Scroll to top of the page when route changes
    window.scrollTo({
      top: 0,
      left: 0,
      behavior: 'instant' // Use 'instant' instead of 'smooth' for immediate response
    })
  }
)
</script>

<template>
  <Header />
  <RouterView />
  <Footer/>
</template>
