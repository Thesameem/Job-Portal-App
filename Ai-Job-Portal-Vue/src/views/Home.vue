<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Cookie from '@/scripts/Cookie'
import axios from 'axios'
import { GET } from '@/scripts/Fetch'
import Header from '@/components/reusable/Header.vue'
import Footer from '@/components/reusable/Footer.vue'

let router = useRouter()

// Authentication is now handled in router/index.js with beforeEach guard
</script>

<template>
  <!-- Hero Section -->
  <section class="hero" role="banner">
    <div class="hero-content">
      <h1>Welcome!!</h1>
      <h1>16,789 Jobs For You</h1>
      <p>
        Connect with top employers and discover opportunities that match your skills and
        aspirations.
      </p>
      <div class="search-container">
        <form action="" method="GET" role="search">
          <input
            type="text"
            name="search"
            placeholder="Search for jobs, skills, or companies"
            aria-label="Search jobs"
          />
        </form>
        <button type="submit" class="search-btn" aria-label="Search jobs">
          <i class="fas fa-search"></i> Search
        </button>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features" aria-labelledby="features-heading">
    <h2 id="features-heading" class="visually-hidden">Our Features</h2>
    <div class="feature-grid">
      <div class="feature-card">
        <i class="fas fa-search" aria-hidden="true"></i>
        <h3>Find Jobs</h3>
        <p>Browse through thousands of job listings from top companies.</p>
        <RouterLink to="/findjob" class="feature-link"
          >Explore Jobs <i class="fas fa-arrow-right" aria-hidden="true"></i
        ></RouterLink>
      </div>
      <div class="feature-card">
        <i class="fas fa-briefcase" aria-hidden="true"></i>
        <h3>Post Jobs</h3>
        <p>Hire the best talent for your company's needs.</p>
        <RouterLink to="/createjob" class="feature-link"
          >Post a Job <i class="fas fa-arrow-right" aria-hidden="true"></i
        ></RouterLink>
      </div>
      <div class="feature-card">
        <i class="fas fa-users" aria-hidden="true"></i>
        <h3>Connect</h3>
        <p>Build your professional network and grow your career.</p>
        <RouterLink to="/findtalent" class="feature-link"
          >Find Talent <i class="fas fa-arrow-right" aria-hidden="true"></i
        ></RouterLink>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section class="how-it-works" aria-labelledby="how-it-works-heading">
    <h2 id="how-it-works-heading">How It Works</h2>
    <div class="steps-container">
      <div class="step">
        <i class="fas fa-user-plus" aria-hidden="true"></i>
        <h3>Create Account</h3>
        <p>Sign up and create your professional profile</p>
        <RouterLink to="/auth?signup=true" class="step-link"
          >Get Started <i class="fas fa-arrow-right" aria-hidden="true"></i
        ></RouterLink>
      </div>
      <div class="step">
        <i class="fas fa-search" aria-hidden="true"></i>
        <h3>Search Jobs</h3>
        <p>Browse and apply for jobs that match your skills</p>
        <RouterLink to="/findjob" class="step-link"
          >Browse Jobs <i class="fas fa-arrow-right" aria-hidden="true"></i
        ></RouterLink>
      </div>
      <div class="step">
        <i class="fas fa-handshake" aria-hidden="true"></i>
        <h3>Get Hired</h3>
        <p>Connect with employers and land your dream job</p>
        <RouterLink to="/findtalent" class="step-link"
          >Find Opportunities <i class="fas fa-arrow-right" aria-hidden="true"></i
        ></RouterLink>
      </div>
    </div>
  </section>
</template>

<style scoped>
/* Existing styles remain unchanged */

/* Updated search styles */
.search-container {
  max-width: 800px;
  margin: 2rem auto;
  width: 100%;
  display: flex;
  gap: 0.8rem;
  align-items: center;
}

.search-container form {
  flex: 1;
  position: relative;
}

.search-container input {
  width: 100%;
  padding: 0.9rem 1.5rem;
  border: none;
  border-radius: 10px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: rgba(255, 255, 255, 0.95);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(10px);
  color: #2d3748;
}

.search-container input::placeholder {
  color: #a0aec0;
  font-weight: 400;
}

.search-container input:focus {
  outline: none;
  box-shadow: 0 4px 25px rgba(99, 106, 232, 0.2);
  background: white;
}

.search-btn {
  padding: 0.9rem 2rem;
  background: #636ae8;
  color: white;
  border: none;
  border-radius: 10px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  box-shadow: 0 4px 15px rgba(99, 106, 232, 0.3);
  height: fit-content;
  white-space: nowrap;
}

.search-btn:hover {
  background: #4f46e5;
  transform: translateY(-1px);
  box-shadow: 0 4px 15px rgba(99, 106, 232, 0.4);
}

.search-btn:active {
  transform: translateY(0);
  box-shadow: 0 2px 8px rgba(99, 106, 232, 0.3);
}

/* Responsive styles */
@media (max-width: 768px) {
  .search-container {
    flex-direction: column;
  }
  
  .search-btn {
    width: 100%;
    justify-content: center;
  }
}
</style>
