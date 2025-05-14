<template>
    <!-- Post Job Section -->
    <section class="post-job-section">
        <div class="post-job-container">
            <div class="post-job-header">
                <h2>Post a New Job</h2>
                <p>Fill out the form below to create your job listing</p>
            </div>

            <form class="post-job-form">
                <!-- Company Information -->
                <div class="form-section">
                    <h3>Company Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="company-name">Company Name</label>
                            <input type="text" id="company-name" required>
                        </div>
                        <div class="form-group">
                            <label for="company-logo">Company Logo</label>
                            <div class="file-upload">
                                <input type="file" id="company-logo" accept="image/*" required>
                                <label for="company-logo" class="file-upload-label">
                                    <i class="fas fa-upload"></i>
                                    <span>Choose Logo</span>
                                </label>
                                <span class="file-name">No file chosen</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company-website">Company Website</label>
                            <input type="url" id="company-website" placeholder="https://">
                        </div>
                        <div class="form-group">
                            <label for="company-size">Company Size</label>
                            <select id="company-size" required>
                                <option value="">Select size</option>
                                <option value="1-10">1-10 employees</option>
                                <option value="11-50">11-50 employees</option>
                                <option value="51-200">51-200 employees</option>
                                <option value="201-500">201-500 employees</option>
                                <option value="501+">501+ employees</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Job Details -->
                <div class="form-section">
                    <h3>Job Details</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="job-title">Job Title</label>
                            <input type="text" id="job-title" placeholder="e.g., Senior Frontend Developer" required>
                        </div>
                        <div class="form-group">
                            <label for="job-type">Job Type</label>
                            <select id="job-type" required>
                                <option value="">Select type</option>
                                <option value="full-time">Full-time</option>
                                <option value="part-time">Part-time</option>
                                <option value="contract">Contract</option>
                                <option value="freelance">Freelance</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="job-location">Location</label>
                            <input type="text" id="job-location" placeholder="e.g., Remote, New York, etc." required>
                        </div>
                        <div class="form-group">
                            <label for="job-salary">Salary Range</label>
                            <input type="text" id="job-salary" placeholder="e.g., $80,000 - $100,000" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="job-description">Job Description</label>
                        <textarea id="job-description" rows="6" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="job-responsibilities">Key Responsibilities</label>
                        <textarea id="job-responsibilities" rows="4" required></textarea>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="form-section">
                    <h3>Requirements</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="experience-level">Experience Level</label>
                            <select id="experience-level" required>
                                <option value="">Select level</option>
                                <option value="entry">Entry Level</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="senior">Senior</option>
                                <option value="lead">Lead</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="education-level">Education Level</label>
                            <select id="education-level" required>
                                <option value="">Select level</option>
                                <option value="high-school">High School</option>
                                <option value="associate">Associate Degree</option>
                                <option value="bachelor">Bachelor's Degree</option>
                                <option value="master">Master's Degree</option>
                                <option value="phd">PhD</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="required-skills">Required Skills (comma separated)</label>
                        <input type="text" id="required-skills" placeholder="e.g., React, JavaScript, CSS, TypeScript" required>
                    </div>
                    <div class="form-group">
                        <label for="preferred-skills">Preferred Skills (comma separated)</label>
                        <input type="text" id="preferred-skills" placeholder="e.g., AWS, Docker, Kubernetes">
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="form-section">
                    <h3>Additional Information</h3>
                    <div class="form-group">
                        <label for="benefits">Benefits & Perks</label>
                        <textarea id="benefits" rows="4" placeholder="List the benefits and perks offered"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="application-deadline">Application Deadline</label>
                        <input type="date" id="application-deadline">
                    </div>
                    <div class="form-group">
                        <label for="contact-email">Contact Email</label>
                        <input type="email" id="contact-email" required>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                    <button type="submit" class="submit-btn">Post Job</button>
                </div>
            </form>
        </div>
    </section>
</template>