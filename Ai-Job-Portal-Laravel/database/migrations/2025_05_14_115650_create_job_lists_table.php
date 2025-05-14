<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_lists', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_logo')->nullable();
            $table->string('company_website')->nullable();
            $table->string('company_size');
            $table->string('title');
            $table->enum('type',['full-time','part-time','contract','freelancer']);
            $table->string('location');
            $table->string('salary_range');
            $table->text('description');
            $table->text('responsibilities');
            $table->enum('experience_level',['entry','intermediate','senior','lead']);
            $table->enum('education_level',['high-school','associate','bachelor','master','phd']);
            $table->string('required_skills');
            $table->string('preferred_skills')->nullable();
            $table->text('benefits')->nullable();
            $table->date('application_deadline')->nullable();
            $table->string('contact_email');
            $table->enum('status',['draft','posted','closed']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_lists');
    }
};
