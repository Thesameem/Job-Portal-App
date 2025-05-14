<template>
  <div class="review-container">
    <h1>Review Applications</h1>

    <!-- Filter Section -->
    <div class="filter-section">
      <select class="filter-select" id="statusFilter">
        <option value="all">All Applications</option>
        <option value="new">New</option>
        <option value="reviewing">Under Review</option>
        <option value="interview">Interview Scheduled</option>
        <option value="rejected">Rejected</option>
      </select>
      <select class="filter-select" id="jobFilter">
        <option value="all">All Jobs</option>
        <option value="frontend">Frontend Developer</option>
        <option value="backend">Backend Developer</option>
        <option value="fullstack">Full Stack Developer</option>
      </select>
    </div>

    <!-- Application Cards -->
    <div class="application-card">
      <div class="applicant-header">
        <img src="./../../images/myphoto.JPG" alt="Applicant" class="applicant-avatar" />
        <div class="applicant-info">
          <h2>John Doe</h2>
          <p>Senior Frontend Developer</p>
          <p>Applied 2 days ago</p>
        </div>
        <span class="status-badge status-new">New</span>
      </div>

      <div class="application-details">
        <div class="detail-row">
          <span class="detail-label">Experience:</span>
          <span class="detail-value">5+ years</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Current Company:</span>
          <span class="detail-value">Tech Solutions Inc.</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Skills:</span>
          <span class="detail-value">React, TypeScript, Node.js, AWS</span>
        </div>
      </div>

      <div class="resume-section">
        <h3>Resume</h3>
        <div class="resume-preview">
          <p>Click to view full resume</p>
        </div>
      </div>

      <div class="action-buttons">
        <button class="action-btn approve-btn">Approve</button>
        <button class="action-btn reject-btn">Reject</button>
        <button class="action-btn message-btn">Message</button>
        <button class="action-btn schedule-btn">Schedule Interview</button>
      </div>
    </div>

    <div class="application-card">
      <div class="applicant-header">
        <img src="./../../images/myphoto.JPG" alt="Applicant" class="applicant-avatar" />
        <div class="applicant-info">
          <h2>Jane Smith</h2>
          <p>Full Stack Developer</p>
          <p>Applied 3 days ago</p>
        </div>
        <span class="status-badge status-reviewing">Under Review</span>
      </div>

      <div class="application-details">
        <div class="detail-row">
          <span class="detail-label">Experience:</span>
          <span class="detail-value">3+ years</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Current Company:</span>
          <span class="detail-value">Digital Innovations</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Skills:</span>
          <span class="detail-value">JavaScript, Python, Django, React</span>
        </div>
      </div>

      <div class="resume-section">
        <h3>Resume</h3>
        <div class="resume-preview">
          <p>Click to view full resume</p>
        </div>
      </div>

      <div class="action-buttons">
        <button class="action-btn approve-btn">Approve</button>
        <button class="action-btn reject-btn">Reject</button>
        <button class="action-btn message-btn">Message</button>
        <button class="action-btn schedule-btn">Schedule Interview</button>
      </div>
    </div>
  </div>
</template>
