<script setup>
import { RouterView, useRoute } from 'vue-router'
import { watch } from 'vue'
import Header from './components/reusable/Header.vue'
import Footer from './components/reusable/Footer.vue'

const route = useRoute()

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
