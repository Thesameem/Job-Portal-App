<script setup>
import { useRouter, useRoute } from 'vue-router'
import { onMounted, ref } from 'vue'
import Cookie from '@/scripts/Cookie'
import { useJobStore } from '@/stores/job'

import LogIn from '@/components/Auth/LogIn.vue'
import SignUp from '@/components/Auth/SignUp.vue'
import ForgotPassword from '@/components/Auth/ForgotPassword.vue'

const loginPage = ref(true)
const showForgotPassword = ref(false)
const router = useRouter()
const route = useRoute()
const jobStore = useJobStore()

const showSignupPage = () => {
  loginPage.value = false
  showForgotPassword.value = false
}

const showLoginPage = () => {
  loginPage.value = true
  showForgotPassword.value = false
}

const showForgotPasswordPage = () => {
  loginPage.value = false
  showForgotPassword.value = true
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
    <LogIn v-if="loginPage" @signup="showSignupPage" @forgot-password="showForgotPasswordPage" />
    <SignUp v-else-if="!loginPage && !showForgotPassword" @login="showLoginPage" />
    <ForgotPassword v-else-if="showForgotPassword" @back-to-login="showLoginPage" />
  </div>

  <!-- <LogIn />
  <SignUp /> -->
</template>
