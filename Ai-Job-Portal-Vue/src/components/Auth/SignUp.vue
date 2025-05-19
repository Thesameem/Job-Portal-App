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
const phoneNumber = ref('')
const password = ref('')
const signupError = ref(false)
const errorText = ref('')
const termsAccepted = ref(false)
const isSubmitting = ref(false)
const showSuccessMessage = ref(false)
const successMessage = ref('')
const showPassword = ref(false)

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
    if (!fullname.value || !email.value || !password.value || !phoneNumber.value) {
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
    formData.append('phone_number', phoneNumber.value)
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
    console.error('Registration error:', error)
    signupError.value = true
    errorText.value = 'An error occurred during registration'
    toast.error('An error occurred during registration')
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
        <h2>Create Account</h2>
        <p>Sign up to start your job search journey</p>
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
            :disabled="isSubmitting"
          />
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input 
            type="email" 
            id="email" 
            placeholder="Enter your email" 
            required 
            v-model="email"
            :disabled="isSubmitting"
          />
        </div>
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input
            type="tel"
            id="phone"
            placeholder="Enter your phone number"
            required
            v-model="phoneNumber"
            :disabled="isSubmitting"
          />
          <small class="field-hint">Format: +1234567890</small>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <div class="password-input">
            <input
              :type="showPassword ? 'text' : 'password'"
              id="password"
              placeholder="Create a password"
              required
              v-model="password"
              :disabled="isSubmitting"
            />
            <i 
              :class="['fas', showPassword ? 'fa-eye-slash' : 'fa-eye', 'toggle-password']"
              @click="showPassword = !showPassword"
              style="cursor:pointer"
              title="Show/Hide Password"
            ></i>
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
          type="submit" 
          class="auth-button" 
          :disabled="!termsAccepted || isSubmitting"
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
  color: #ef4444;
  font-size: 0.875rem;
  margin-top: 0.5rem;
}

.field-hint {
  color: #6b7280;
  font-size: 0.75rem;
  margin-top: 0.25rem;
}

.auth-links {
  text-align: center;
  margin-top: 1rem;
}

.auth-links a {
  color: #636ae8;
  text-decoration: none;
}

.auth-links a:hover {
  text-decoration: underline;
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
