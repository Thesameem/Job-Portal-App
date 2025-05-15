<script setup>
import { useRouter } from 'vue-router'
import { onMounted, ref } from 'vue'
import Cookie from '@/scripts/Cookie'
import { useJobStore } from '@/stores/job'

const loginPage = ref(true)
const router = useRouter()
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
  // Check if user is already authenticated
  const token = Cookie.getCookie('job-app')
  if (token && Object.keys(jobStore.user).length > 0) {
    // If already authenticated, redirect to home
    console.log('User already authenticated, redirecting to home...')
    router.replace('/')
  }
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
