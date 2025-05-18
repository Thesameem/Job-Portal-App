<script setup>
import axios from 'axios'
import { useRouter, useRoute } from 'vue-router'
import { useJobStore } from '@/stores/job'
import Cookie from '@/scripts/Cookie'
import { POST } from '@/scripts/Fetch'
import { ref, nextTick } from 'vue'
import { useToast } from '@/scripts/toast'

const emits = defineEmits(['signup', 'forgot-password'])
const toast = useToast()

const email = ref('')
const password = ref('')
const loginError = ref(false)
const errorMessage = ref('')
const isSubmitting = ref(false)
const showPassword = ref(false)

const jobStore = useJobStore()
const router = useRouter()
const route = useRoute()

const LogInUser = async () => {
  try {
    // Reset error state
    loginError.value = false
    errorMessage.value = ''
    
    // Validate input
    if (!email.value || !password.value) {
      loginError.value = true
      errorMessage.value = 'Please enter both email and password'
      toast.error('Please enter both email and password')
      return
    }
    
    // Set submitting state
    isSubmitting.value = true
    
    // Create form data to send to server
    const formData = new FormData()
    formData.append('email', email.value)
    formData.append('password', password.value)

    // Send post method to login user
    console.log('Submitting login with:', { email: email.value, password: 'XXXXX' })
    const result = await POST('user/login', formData)
    
    // Log the response for debugging
    console.log('Login response:', result)
    
    if (!result.error) {
      // Login successful
      console.log('Login successful, saving token and user data')
      
      // Save token first
      if (result.response.token) {
        Cookie.setCookie('job-app', result.response.token, 30)
      } else {
        console.warn('No token found in successful response')
      }
      
      // Wait for a tick to ensure cookie is set
      await nextTick()
      
      // Then set user data to trigger watchers
      if (result.response.user) {
        // First clear user data to ensure watchers trigger
        jobStore.user = {}
        
        // Wait a tick
        await nextTick()
        
        // Then set the new user data
        jobStore.user = result.response.user
        console.log('User data saved to store:', jobStore.user)
        
        // Save to localStorage for persistence
        localStorage.setItem('job-user', JSON.stringify(result.response.user))
      } else {
        console.warn('No user data found in successful response')
      }
      
      // Force header to check auth immediately
      if (window.checkAuthNow) {
        const isAuth = window.checkAuthNow()
        console.log('Forced auth check result:', isAuth)
      }
      
      // Store the welcome message and user name for after navigation
      const userName = result.response.user?.fullname || 'User'
      
      // Determine where to redirect the user
      const redirectPath = route.query.redirect || '/'
      
      // Navigate to the redirect path or home
      console.log(`Redirecting to: ${redirectPath}`)
      
      // First navigate, then show toast after navigation is complete
      await router.replace(redirectPath)
      
      // Show welcome toast after a short delay to ensure page has loaded
      setTimeout(() => {
        toast.success(`Welcome back, ${userName}!`)
      }, 500)
    } else {
      // Login failed
      console.log('Login failed:', result.reason)
      loginError.value = true
      errorMessage.value = result.reason || 'Login failed. Please check your credentials.'
      toast.error(errorMessage.value)
    }
  } catch (error) {
    // This should only happen for critical errors
    console.error('Critical login error:', error)
    loginError.value = true
    errorMessage.value = 'A critical error occurred. Please try again later.'
    toast.error(errorMessage.value)
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <!-- Log In Section -->
  <section class="auth-section">
    <div class="auth-container">
      <div class="auth-header">
        <h2>Welcome Back</h2>
        <p>Log in to your Ai-Job account to continue</p>
      </div>
      <form class="auth-form" @submit.prevent="LogInUser">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" placeholder="Enter your email" required v-model="email" :disabled="isSubmitting" />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <div class="password-input">
            <input
              :type="showPassword ? 'text' : 'password'"
              id="password"
              placeholder="Enter your password"
              required
              v-model="password"
              :disabled="isSubmitting"
            />
            
            <i 
            :class="['fas', showPassword ? 'fa-eye-slash' : 'fa-eye', 'toggle-password']"
              @click="showPassword = !showPassword"
              style="cursor:pointer"
              title="Show/Hide Password"></i>
          </div>
        </div>
        <div class="form-options">
          <label class="remember-me">
            <input type="checkbox" />
            Remember Me
          </label>
          <a href="#" class="forgot-password" @click.prevent="emits('forgot-password')">Forgot Password?</a>
        </div>
        
        <!-- Error message -->
        <div v-if="loginError" class="error-message">
          {{ errorMessage }}
        </div>
        
        <button type="submit" class="auth-button" :disabled="isSubmitting">
          {{ isSubmitting ? 'Logging in...' : 'Log In' }}
        </button>
        <div class="social-login">
          <p>Or log in with</p>
          <div class="social-buttons">
            <button type="button" class="social-btn google" :disabled="isSubmitting">
              <i class="fab fa-google"></i>
              Google
            </button>
            <button type="button" class="social-btn linkedin" :disabled="isSubmitting">
              <i class="fab fa-linkedin"></i>
              LinkedIn
            </button>
          </div>
        </div>
      </form>
      <div class="auth-footer">
        <p>Don't have an account? <a href="#" @click.prevent="emits('signup')">Sign Up</a></p>
      </div>
    </div>
  </section>
</template>

<style scoped>
.error-message {
  color: #e74c3c;
  background-color: rgba(231, 76, 60, 0.1);
  padding: 10px;
  border-radius: 4px;
  margin-bottom: 15px;
  text-align: center;
}

.auth-button {
  width: 100%;
  padding: 12px;
  background-color: #0d6efd;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s;
  margin-bottom: 15px;
}

.auth-button:hover:not(:disabled) {
  background-color: #0b5ed7;
}

.auth-button:disabled {
  background-color: #6c757d;
  cursor: not-allowed;
}
</style>
