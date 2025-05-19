<script setup>
import { ref, onMounted } from 'vue'
import { POST } from '@/scripts/Fetch'
import { useToast } from '@/scripts/toast'

const props = defineProps({
  phoneNumber: {
    type: String,
    required: true
  },
  resetToken: {
    type: String,
    required: true
  }
})

// Debug logging
onMounted(() => {
  console.log('ResetPassword mounted with props:', {
    phoneNumber: props.phoneNumber,
    resetToken: props.resetToken
  })
})

const emits = defineEmits(['back-to-login'])
const toast = useToast()

const password = ref('')
const confirmPassword = ref('')
const isSubmitting = ref(false)
const errorMessage = ref('')
const showError = ref(false)
const showSuccess = ref(false)
const successMessage = ref('')
const showPassword = ref(false)
const showConfirmPassword = ref(false)

const handleSubmit = async () => {
  try {
    // Reset states
    showError.value = false
    errorMessage.value = ''
    showSuccess.value = false
    successMessage.value = ''
    
    // Validate passwords
    if (!password.value || !confirmPassword.value) {
      showError.value = true
      errorMessage.value = 'Please enter both password fields'
      return
    }
    
    if (password.value !== confirmPassword.value) {
      showError.value = true
      errorMessage.value = 'Passwords do not match'
      return
    }
    
    if (password.value.length < 8) {
      showError.value = true
      errorMessage.value = 'Password must be at least 8 characters long'
      return
    }
    
    isSubmitting.value = true
    
    // Create form data
    const formData = new FormData()
    formData.append('phone_number', props.phoneNumber)
    formData.append('reset_token', props.resetToken)
    formData.append('password', password.value)
    
    console.log('Sending password reset request with:', {
      phoneNumber: props.phoneNumber,
      resetToken: props.resetToken
    })
    
    // Send request
    const result = await POST('password/reset', formData)
    
    if (!result.error) {
      showSuccess.value = true
      successMessage.value = 'Password reset successfully'
      toast.success('Password reset successfully!')
      
      // Redirect to login after 2 seconds
      setTimeout(() => {
        emits('back-to-login')
      }, 2000)
    } else {
      showError.value = true
      errorMessage.value = result.reason || 'Failed to reset password'
      toast.error(errorMessage.value)
    }
  } catch (error) {
    console.error('Password reset error:', error)
    showError.value = true
    errorMessage.value = 'An error occurred. Please try again.'
    toast.error('An error occurred. Please try again.')
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <section class="auth-section">
    <div class="auth-container">
      <div class="auth-header">
        <h2>Reset Password</h2>
        <p>Enter your new password</p>
      </div>
      
      <form class="auth-form" @submit.prevent="handleSubmit">
        <div class="form-group">
          <label for="password">New Password</label>
          <div class="password-input">
            <input
              :type="showPassword ? 'text' : 'password'"
              id="password"
              placeholder="Enter new password"
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
          <small class="field-hint">Password must be at least 8 characters long</small>
        </div>
        
        <div class="form-group">
          <label for="confirm-password">Confirm Password</label>
          <div class="password-input">
            <input
              :type="showConfirmPassword ? 'text' : 'password'"
              id="confirm-password"
              placeholder="Confirm new password"
              required
              v-model="confirmPassword"
              :disabled="isSubmitting"
            />
            <i 
              :class="['fas', showConfirmPassword ? 'fa-eye-slash' : 'fa-eye', 'toggle-password']"
              @click="showConfirmPassword = !showConfirmPassword"
              style="cursor:pointer"
              title="Show/Hide Password"
            ></i>
          </div>
        </div>
        
        <!-- Error message -->
        <div v-if="showError" class="error-message">
          {{ errorMessage }}
        </div>
        
        <!-- Success message -->
        <div v-if="showSuccess" class="success-message">
          {{ successMessage }}
        </div>
        
        <button type="submit" class="auth-button" :disabled="isSubmitting">
          {{ isSubmitting ? 'Resetting...' : 'Reset Password' }}
        </button>
        
        <div class="auth-links">
          <a href="#" @click.prevent="emits('back-to-login')">Back to Login</a>
        </div>
      </form>
    </div>
  </section>
</template>

<style scoped>
.error-message {
  color: #ef4444;
  font-size: 0.875rem;
  margin-top: 0.5rem;
}

.success-message {
  color: #10b981;
  font-size: 0.875rem;
  margin-top: 0.5rem;
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

.field-hint {
  color: #6b7280;
  font-size: 0.75rem;
  margin-top: 0.25rem;
}
</style> 