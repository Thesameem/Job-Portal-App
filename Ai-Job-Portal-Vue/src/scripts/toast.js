// Simple toast notification system
const createToastContainer = () => {
  // Check if container already exists
  let container = document.getElementById('toast-container')
  
  if (!container) {
    // Create container if it doesn't exist
    container = document.createElement('div')
    container.id = 'toast-container'
    container.style.position = 'fixed'
    container.style.top = '20px'
    container.style.right = '20px'
    container.style.zIndex = '9999'
    document.body.appendChild(container)
  }
  
  return container
}

// Create a toast element
const createToast = (message, type) => {
  const toast = document.createElement('div')
  toast.className = `toast toast-${type}`
  toast.style.minWidth = '250px'
  toast.style.margin = '0 0 10px 0'
  toast.style.padding = '15px'
  toast.style.borderRadius = '4px'
  toast.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.2)'
  toast.style.opacity = '0'
  toast.style.pointerEvents = 'auto'
  toast.style.cursor = 'pointer'
  toast.style.transition = 'opacity 0.3s ease-in-out'
  
  // Set color based on type
  switch (type) {
    case 'success':
      toast.style.backgroundColor = '#4CAF50'
      toast.style.color = 'white'
      break
    case 'error':
      toast.style.backgroundColor = '#F44336'
      toast.style.color = 'white'
      break
    case 'warning':
      toast.style.backgroundColor = '#FF9800'
      toast.style.color = 'white'
      break
    case 'info':
      toast.style.backgroundColor = '#2196F3'
      toast.style.color = 'white'
      break
  }
  
  // Add message
  toast.textContent = message
  
  // Add click to dismiss
  toast.addEventListener('click', () => {
    if (toast.parentNode) {
      fadeOutToast(toast)
    }
  })
  
  return toast
}

// Function to fade out and remove a toast
const fadeOutToast = (toast) => {
  toast.style.opacity = '0'
  setTimeout(() => {
    if (toast && toast.parentNode) {
      toast.parentNode.removeChild(toast)
    }
  }, 300)
}

// Add CSS for animations
const addToastStyles = () => {
  if (!document.getElementById('toast-styles')) {
    const style = document.createElement('style')
    style.id = 'toast-styles'
    style.textContent = `
      .toast {
        transform: translateY(0);
      }
    `
    document.head.appendChild(style)
  }
}

// Active toasts tracking
const activeToasts = new Set()

// Show a toast notification
const showToast = (message, type, duration = 3000) => {
  try {
    // Ensure minimum duration of 3 seconds
    duration = Math.max(3000, duration)
    
    addToastStyles()
    const container = createToastContainer()
    const toast = createToast(message, type)
    
    // Add to container
    container.appendChild(toast)
    
    // Track this toast
    const toastId = Date.now() + Math.random()
    activeToasts.add(toastId)
    
    // Force reflow to ensure transition works
    toast.getBoundingClientRect()
    
    // Fade in the toast
    setTimeout(() => {
      toast.style.opacity = '1'
    }, 10)
    
    // Wait for page load events before starting the timer
    const startTimer = () => {
      // Only start timer if toast still exists
      if (!activeToasts.has(toastId)) return
      
      // Remove from tracking
      activeToasts.delete(toastId)
      
      // Start the removal timer
      setTimeout(() => {
        if (toast && toast.parentNode) {
          fadeOutToast(toast)
        }
      }, duration)
    }
    
    // Start timer when page is fully loaded or after a delay
    if (document.readyState === 'complete') {
      // Page already loaded, wait a bit to ensure any transitions are done
      setTimeout(startTimer, 500)
    } else {
      // Wait for page load
      window.addEventListener('load', () => {
        setTimeout(startTimer, 500)
      }, { once: true })
      
      // Fallback in case load event doesn't fire
      setTimeout(startTimer, 5000)
    }
    
    return toastId
  } catch (err) {
    console.error('Error showing toast:', err)
    console.log(message)
    return false
  }
}

// Export the toast functions
export const useToast = () => {
  return {
    success: (message) => showToast(message, 'success', 3000),
    error: (message) => showToast(message, 'error', 5000), // Show errors longer
    warning: (message) => showToast(message, 'warning', 4000),
    info: (message) => showToast(message, 'info', 3000)
  }
} 