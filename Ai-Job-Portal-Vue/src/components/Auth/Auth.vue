<script setup>
import { ref, watch, onMounted } from 'vue'
import LogIn from './LogIn.vue'
import SignUp from './SignUp.vue'
import ForgotPassword from './ForgotPassword.vue'
import ResetPassword from './ResetPassword.vue'

const showLogin = ref(true)
const showForgotPassword = ref(false)
const showResetPassword = ref(false)
const resetData = ref(null)

// Debug watchers
watch(showResetPassword, (newVal) => {
  console.log('showResetPassword changed:', newVal)
})

watch(resetData, (newVal) => {
  console.log('resetData changed:', newVal)
}, { deep: true })

// Log initial state
onMounted(() => {
  console.log('Auth component mounted with initial state:', {
    showLogin: showLogin.value,
    showForgotPassword: showForgotPassword.value,
    showResetPassword: showResetPassword.value,
    resetData: resetData.value
  })
})

const handleForgotPassword = () => {
  console.log('Handling forgot password')
  showLogin.value = false
  showForgotPassword.value = true
  showResetPassword.value = false
  resetData.value = null
}

const handleBackToLogin = () => {
  console.log('Handling back to login')
  showLogin.value = true
  showForgotPassword.value = false
  showResetPassword.value = false
  resetData.value = null
}

const handleVerifyOtp = (data) => {
  console.log('Handling OTP verification with data:', data)
  
  // Immediately update all states
  showLogin.value = false
  showForgotPassword.value = false
  showResetPassword.value = true
  resetData.value = {
    phoneNumber: data.phoneNumber,
    resetToken: data.resetToken
  }
  
  // Log the final state
  console.log('State after OTP verification:', {
    showLogin: showLogin.value,
    showForgotPassword: showForgotPassword.value,
    showResetPassword: showResetPassword.value,
    resetData: resetData.value
  })
}
</script>

<template>
  <div class="auth-wrapper">
    <LogIn v-if="showLogin" @signup="showLogin = false" @forgot-password="handleForgotPassword" />
    <SignUp v-else-if="!showLogin && !showForgotPassword && !showResetPassword" @login="showLogin = true" />
    <ForgotPassword 
      v-else-if="showForgotPassword" 
      @back-to-login="handleBackToLogin"
      @verify-otp="handleVerifyOtp"
    />
    <ResetPassword 
      v-else-if="showResetPassword && resetData"
      :phone-number="resetData.phoneNumber"
      :reset-token="resetData.resetToken"
      @back-to-login="handleBackToLogin"
    />
    <div v-else class="error-state">
      <p>Error: Invalid state</p>
      <button @click="handleBackToLogin">Back to Login</button>
    </div>
  </div>
</template>

<style scoped>
.auth-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.error-state {
  text-align: center;
  padding: 2rem;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.error-state button {
  margin-top: 1rem;
  padding: 0.5rem 1rem;
  background: #636ae8;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.error-state button:hover {
  background: #4f46e5;
}
</style> 