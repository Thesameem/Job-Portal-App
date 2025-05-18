<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('professional_title')->nullable();
            $table->string('location')->nullable();
            $table->string('phone')->nullable();
            $table->text('about')->nullable();
            
            // Profile photo
            $table->string('profile_photo')->nullable();
            
            // Skills
            $table->text('frontend_skills')->nullable();
            $table->text('backend_skills')->nullable();
            $table->text('other_skills')->nullable();
            
            // Work experience fields
            $table->json('work_experience')->nullable();
            
            // Education fields
            $table->json('education')->nullable();
            
            // Availability information
            $table->string('availability_status')->nullable();
            $table->string('preferred_work_type')->nullable();
            $table->string('notice_period')->nullable();
            $table->string('expected_salary')->nullable();
            
            // Languages
            $table->json('languages')->nullable();
            
            // Certifications
            $table->json('certifications')->nullable();
            
            // Social links
            $table->string('github_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('portfolio_url')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
}; 