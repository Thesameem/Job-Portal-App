<script setup>
import { ref } from 'vue'
import { POST } from '@/scripts/Fetch'
import { useToast } from '@/scripts/toast'

const emits = defineEmits(['back-to-login'])
const toast = useToast()

const phoneNumber = ref('')
const isSubmitting = ref(false)
const errorMessage = ref('')
const showError = ref(false)
const showSuccess = ref(false)
const successMessage = ref('')
const otp = ref('')
const showOtpInput = ref(false)
const showPasswordReset = ref(false)
const resetToken = ref('')

// Password reset form fields
const password = ref('')
const confirmPassword = ref('')
const showPassword = ref(false)
const showConfirmPassword = ref(false)

const handleSubmit = async () => {
  try {
    // Reset states
    showError.value = false
    errorMessage.value = ''
    showSuccess.value = false
    successMessage.value = ''
    
    // Validate phone number
    if (!phoneNumber.value) {
      showError.value = true
      errorMessage.value = 'Please enter your phone number'
      toast.error('Please enter your phone number')
      return
    }

    // Format phone number to ensure it starts with +
    let formattedPhone = phoneNumber.value
    if (!formattedPhone.startsWith('+')) {
      formattedPhone = '+' + formattedPhone
    }
    
    isSubmitting.value = true
    
    // Create form data
    const formData = new FormData()
    formData.append('phone_number', formattedPhone)
    
    // Send request
    const result = await POST('password/request-otp', formData)
    
    if (!result.error) {
      toast.success('OTP sent successfully!')
      // Show OTP input after successful OTP sending
      showOtpInput.value = true
    } else {
      showError.value = true
      errorMessage.value = result.reason || 'Failed to process request'
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

const verifyOtp = async () => {
  try {
    // Reset states
    showError.value = false
    errorMessage.value = ''
    
    // Validate OTP
    if (!otp.value) {
      showError.value = true
      errorMessage.value = 'Please enter the OTP'
      toast.error('Please enter the OTP')
      return
    }
    
    isSubmitting.value = true
    
    // Create form data
    const formData = new FormData()
    formData.append('phone_number', phoneNumber.value)
    formData.append('otp', otp.value)
    
    // Send request
    const result = await POST('password/verify-otp', formData)
    
    if (!result.error) {
      toast.success('OTP verified successfully!')
      
      // Store reset token and show password reset form
      resetToken.value = result.response.reset_token
      showOtpInput.value = false
      showPasswordReset.value = true
    } else {
      showError.value = true
      errorMessage.value = result.reason || 'Failed to verify OTP'
      toast.error(errorMessage.value)
    }
  } catch (error) {
    console.error('OTP verification error:', error)
    showError.value = true
    errorMessage.value = 'An error occurred. Please try again.'
    toast.error('An error occurred. Please try again.')
  } finally {
    isSubmitting.value = false
  }
}

const resetPassword = async () => {
  try {
    // Reset states
    showError.value = false
    errorMessage.value = ''
    
    // Validate passwords
    if (!password.value || !confirmPassword.value) {
      showError.value = true
      errorMessage.value = 'Please enter both password fields'
      toast.error('Please enter both password fields')
      return
    }
    
    if (password.value !== confirmPassword.value) {
      showError.value = true
      errorMessage.value = 'Passwords do not match'
      toast.error('Passwords do not match')
      return
    }
    
    if (password.value.length < 8) {
      showError.value = true
      errorMessage.value = 'Password must be at least 8 characters long'
      toast.error('Password must be at least 8 characters long')
      return
    }
    
    isSubmitting.value = true
    
    // Create form data
    const formData = new FormData()
    formData.append('phone_number', phoneNumber.value)
    formData.append('reset_token', resetToken.value)
    formData.append('password', password.value)
    
    // Send request
    const result = await POST('password/reset', formData)
    
    if (!result.error) {
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
        <h2>Forgot Password</h2>
        <p v-if="!showOtpInput && !showPasswordReset">Enter your phone number to reset your password</p>
        <p v-else-if="showOtpInput">Enter the OTP sent to your phone</p>
        <p v-else>Enter your new password</p>
      </div>
      
      <!-- Phone Number Form -->
      <form v-if="!showOtpInput && !showPasswordReset" class="auth-form" @submit.prevent="handleSubmit">
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
        
        <button type="submit" class="auth-button" :disabled="isSubmitting">
          {{ isSubmitting ? 'Processing...' : 'Send OTP' }}
        </button>
        
        <div class="auth-links">
          <a href="#" @click.prevent="emits('back-to-login')">Back to Login</a>
        </div>
      </form>
      
      <!-- OTP Verification Form -->
      <form v-else-if="showOtpInput" class="auth-form" @submit.prevent="verifyOtp">
        <div class="form-group">
          <label for="otp">OTP</label>
          <input 
            type="text" 
            id="otp" 
            placeholder="Enter the OTP" 
            required 
            v-model="otp"
            :disabled="isSubmitting"
            maxlength="6"
          />
          <small class="field-hint">Enter the 6-digit code sent to your phone</small>
        </div>
        
        <button type="submit" class="auth-button" :disabled="isSubmitting">
          {{ isSubmitting ? 'Verifying...' : 'Verify OTP' }}
        </button>
        
        <div class="auth-links">
          <a href="#" @click.prevent="showOtpInput = false">Change Phone Number</a>
        </div>
      </form>
      
      <!-- Password Reset Form -->
      <form v-else class="auth-form" @submit.prevent="resetPassword">
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

.password-input {
  position: relative;
}

.toggle-password {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  color: #6b7280;
}
</style> 