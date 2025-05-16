<script setup>
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useJobStore } from '@/stores/job'
import Cookie from '@/scripts/Cookie'
import { POST } from '@/scripts/Fetch'
import { ref } from 'vue'

const emits = defineEmits(['signup'])

const email = ref('')
const password = ref('')
const loginError = ref(false)
const errorMessage = ref('')

const jobStore = useJobStore()
const router = useRouter()

const LogInUser = async () => {
  try {
    // Reset error state
    loginError.value = false
    
    // Validate input
    if (!email.value || !password.value) {
      loginError.value = true
      errorMessage.value = 'Please enter both email and password'
      return
    }
    
    // Create form data to send to server
    const formData = new FormData()
    formData.append('email', email.value)
    formData.append('password', password.value)

    // Send post method to login user
    console.log('Submitting login with:', { email: email.value, password: 'XXXXX' })
    const result = await POST('user/login', formData)
    console.log('Login result:', result)
    
    if (!result.error) {
      // Save token and user data
      Cookie.setCookie('job-app', result.response.token, 30)
      jobStore.user = result.response.user
      
      // Navigate to home page
      console.log('Login successful, redirecting to home...')
      router.push('/')
    } else {
      // Handle login error
      loginError.value = true
      errorMessage.value = result.reason || 'Login failed. Please check your credentials.'
    }
  } catch (error) {
    console.error('Login error:', error)
    loginError.value = true
    errorMessage.value = 'An unexpected error occurred. Please try again.'
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
          <input type="email" id="email" placeholder="Enter your email" required v-model="email" />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <div class="password-input">
            <input
              type="password"
              id="password"
              placeholder="Enter your password"
              required
              v-model="password"
            />
            <i class="fas fa-eye toggle-password"></i>
          </div>
        </div>
        <div class="form-options">
          <label class="remember-me">
            <input type="checkbox" />
            Remember Me
          </label>
          <a href="#" class="forgot-password">Forgot Password?</a>
        </div>
        
        <!-- Error message -->
        <div v-if="loginError" class="error-message">
          {{ errorMessage }}
        </div>
        
        <button type="submit" class="auth-button">Log In</button>
        <div class="social-login">
          <p>Or log in with</p>
          <div class="social-buttons">
            <button type="button" class="social-btn google">
              <i class="fab fa-google"></i>
              Google
            </button>
            <button type="button" class="social-btn linkedin">
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

.auth-button:hover {
  background-color: #0b5ed7;
}
</style>
