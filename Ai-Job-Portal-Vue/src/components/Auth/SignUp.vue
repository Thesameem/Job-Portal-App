<script setup>
import { ref } from 'vue'
import { POST } from '@/scripts/Fetch'
import { useRouter } from 'vue-router'
import Cookie from '@/scripts/Cookie'
import { useJobStore } from '@/stores/job'
import { RouterLink } from 'vue-router'

const emits = defineEmits(['login'])
const router = useRouter()
const jobStore = useJobStore()

const fullname = ref('')
const email = ref('')
const password = ref('')
const signupError = ref(false)
const errorText = ref('')
const termsAccepted = ref(false)

const RegisterUser = async (e) => {
  try {
    // Reset error state
    signupError.value = false
    
    // Validate input
    if (!fullname.value || !email.value || !password.value) {
      signupError.value = true
      errorText.value = 'All fields are required'
      return
    }
    
    if (!termsAccepted.value) {
      signupError.value = true
      errorText.value = 'Please accept the Terms of Service and Privacy Policy'
      return
    }
    
    // Create form data to send to server
    const formData = new FormData()
    formData.append('fullname', fullname.value)
    formData.append('email', email.value)
    formData.append('password', password.value)

    // Send post method to register user
    const result = await POST('user/register', formData)

    if (!result.error) {
      // If registration succeeds and login is automatic
      if (result.response && result.response.token) {
        // Save token and redirect to home
        Cookie.setCookie('job-app', result.response.token, 30)
        if (result.response.user) {
          jobStore.user = result.response.user
        }
        router.push('/')
      } else {
        // If login is not automatic, redirect to login
        emits('login')
      }
    } else {
      signupError.value = true
      errorText.value = result.reason || 'Registration failed. Please try again.'
    }
  } catch (error) {
    console.error('Signup error:', error)
    signupError.value = true
    errorText.value = 'An unexpected error occurred. Please try again.'
  }
}
</script>

<template>
  <!-- Sign Up Section -->
  <section class="auth-section">
    <div class="auth-container">
      <div class="auth-header">
        <h2>Create Your Account</h2>
        <p>Join Ai-Job and start your journey to success</p>
      </div>
      <form class="auth-form" @submit.prevent="RegisterUser">
        <div class="form-group">
          <label for="fullname">Full Name</label>
          <input
            type="text"
            id="fullname"
            placeholder="Enter your full name"
            required
            v-model="fullname"
          />
        </div>
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
              placeholder="Create a password"
              required
              v-model="password"
            />
            <i class="fas fa-eye toggle-password"></i>
          </div>
        </div>
        <div class="form-group" style="display: none">
          <label for="confirm-password">Confirm Password</label>
          <div class="password-input">
            <input
              type="password"
              id="confirm-password"
              placeholder="Confirm your password"
              required
            />
            <i class="fas fa-eye toggle-password"></i>
          </div>
        </div>
        <div class="form-group">
          <label class="checkbox-label">
            <input type="checkbox" required v-model="termsAccepted" />
            <span>
              I agree to the 
              <RouterLink to="/terms">Terms of Service</RouterLink> and
              <RouterLink to="/privacypolicy">Privacy Policy</RouterLink>
            </span>
          </label>
        </div>
        
        <!-- Error message -->
        <div v-if="signupError" class="error-message">
          {{ errorText }}
        </div>

        <button type="submit" class="auth-button" :disabled="!termsAccepted">
          Create Account
        </button>
        <div class="social-login">
          <p>Or sign up with</p>
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
        <p>Already have an account? <a href="#" @click.prevent="emits('login')">Log In</a></p>
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
