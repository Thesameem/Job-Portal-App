<template>
  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1>Contact Us</h1>
      <p>We'd love to hear from you! Reach out to us with any questions or feedback.</p>
    </div>
  </section>

  <!-- Contact Form Section -->
  <section class="contact-form">
    <h2>Get in Touch</h2>
    <form>
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" placeholder="Enter your name" required />
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Enter your email" required />
      </div>
      <div class="form-group">
        <label for="message">Message</label>
        <textarea id="message" placeholder="Enter your message" required></textarea>
      </div>
      <button type="submit" class="submit-button">Send Message</button>
    </form>
  </section>

  <!-- Contact Information Section -->
  <section class="contact-info">
    <h2>Contact Information</h2>
    <div class="info-grid">
      <div class="info-card">
        <i class="fas fa-map-marker-alt"></i>
        <h3>Address</h3>
        <p>123 Ai-Job Street, City, Country</p>
      </div>
      <div class="info-card">
        <i class="fas fa-phone"></i>
        <h3>Phone</h3>
        <p>+1 234 567 890</p>
      </div>
      <div class="info-card">
        <i class="fas fa-envelope"></i>
        <h3>Email</h3>
        <p>info@ai-job.com</p>
      </div>
    </div>
  </section>
</template>
