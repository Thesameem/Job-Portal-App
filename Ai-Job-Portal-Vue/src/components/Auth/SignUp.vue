<script setup>
import { ref, onMounted, computed } from 'vue'
import { POST } from '@/scripts/Fetch'
import { useRouter } from 'vue-router'
import Cookie from '@/scripts/Cookie'
import { useJobStore } from '@/stores/job'
import { RouterLink } from 'vue-router'
import axios from 'axios'
import Config from '@/scripts/Config'
import { useToast } from '@/scripts/toast'

const emits = defineEmits(['login'])
const router = useRouter()
const jobStore = useJobStore()
const toast = useToast()

const fullname = ref('')
const email = ref('')
const password = ref('')
const signupError = ref(false)
const errorText = ref('')
const termsAccepted = ref(false)
const isSubmitting = ref(false)
const showSuccessMessage = ref(false)
const successMessage = ref('')

// Function to scroll to top of the page
const scrollToTop = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  })
}

// Direct handler for the button click
const RegisterUser = async () => {
  console.log('Sign up button clicked')
  
  try {
    // Reset error state
    signupError.value = false
    errorText.value = ''
    showSuccessMessage.value = false
    successMessage.value = ''
    
    // Validate input
    if (!fullname.value || !email.value || !password.value) {
      signupError.value = true
      errorText.value = 'All fields are required'
      toast.error('All fields are required')
      return
    }
    
    if (!termsAccepted.value) {
      signupError.value = true
      errorText.value = 'Please accept the Terms of Service and Privacy Policy'
      toast.error('Please accept the Terms of Service and Privacy Policy')
      return
    }
    
    // Set loading state
    isSubmitting.value = true
    
    // Create form data to send to server
    let formData = new FormData()
    formData.append('fullname', fullname.value)
    formData.append('email', email.value)
    formData.append('password', password.value)
    
    console.log('Sending registration request...')
    
    // Send post method to register user
    let result = await POST('user/register', formData)
    
    console.log('Registration result:', result)
    
    if (!result.error) {
      // Show success toast notification
      toast.success('Account created successfully! Redirecting to login...')
      
      // Scroll to top first, then emit login
      scrollToTop()
      
      // Use emit to switch to login instead of routing
      setTimeout(() => {
        emits('login')
      }, 2000)
    } else {
      signupError.value = true
      errorText.value = result.reason || 'Registration failed. Please try again.'
      toast.error(result.reason || 'Registration failed. Please try again.')
    }
  } catch (error) {
    console.error('Signup error:', error)
    signupError.value = true
    errorText.value = 'An unexpected error occurred. Please try again.'
    toast.error('An unexpected error occurred. Please try again.')
  } finally {
    isSubmitting.value = false
  }
}

// Check if form can be submitted (for button disabling)
const canSubmit = computed(() => {
  return termsAccepted.value && !isSubmitting.value
})

// When the component is mounted, scroll to top
onMounted(() => {
  scrollToTop()
})
</script>

<template>
  <!-- Sign Up Section -->
  <section class="auth-section">
    <div class="auth-container">
      <div class="auth-header">
        <h2>Create Your Account</h2>
        <p>Join Ai-Job and start your journey to success</p>
      </div>
      <!-- Use both form submit and direct button click -->
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
            <input type="checkbox" v-model="termsAccepted" />
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

        <!-- Button with both click and submit handlers for maximum compatibility -->
        <button 
          type="button" 
          class="auth-button" 
          :disabled="!termsAccepted"
          @click="RegisterUser"
        >
          {{ isSubmitting ? 'Creating Account...' : 'Create Account' }}
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

.success-message {
  color: #2ecc71;
  background-color: rgba(46, 204, 113, 0.1);
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

.auth-button:disabled {
  background-color: #a0c0f0;
  cursor: not-allowed;
}

.direct-button {
  display: none; /* Hide the direct button by default, only using as fallback */
}
</style>
