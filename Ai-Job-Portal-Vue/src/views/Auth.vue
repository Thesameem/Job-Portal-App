<script setup>
import { useRouter, useRoute } from 'vue-router'
import { onMounted, ref } from 'vue'
import Cookie from '@/scripts/Cookie'
import { useJobStore } from '@/stores/job'

const loginPage = ref(true)
const router = useRouter()
const route = useRoute()
const jobStore = useJobStore()

import LogIn from '@/components/Auth/LogIn.vue'
import SignUp from '@/components/Auth/SignUp.vue'

const showSignupPage = () => {
  loginPage.value = false
}

const showLoginPage = () => {
  loginPage.value = true
}

onMounted(() => {
  // Check if the signup query parameter is present
  if (route.query.signup === 'true') {
    loginPage.value = false
  }
  
  // Authentication redirect is now handled by router.beforeEach in index.js
})
</script>

<template>
  <div>
    <LogIn v-if="loginPage" @signup="showSignupPage" />
    <SignUp v-else @login="showLoginPage" />
  </div>

  <!-- <LogIn />
  <SignUp /> -->
</template>
