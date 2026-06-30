<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['google_workspace', 'aws_ses']);
            $table->string('from_name');
            $table->string('from_email');
            $table->string('reply_to')->nullable();
            $table->string('smtp_host')->nullable();
            $table->unsignedInteger('smtp_port')->nullable();
            $table->string('smtp_username')->nullable();
            $table->text('smtp_password')->nullable();
            $table->enum('smtp_encryption', ['tls', 'ssl'])->nullable();
            $table->text('aws_key')->nullable();
            $table->text('aws_secret')->nullable();
            $table->string('aws_region')->nullable();
            $table->unsignedInteger('daily_limit')->default(0);
            $table->unsignedInteger('hourly_limit')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('email_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('document')->nullable();
            $table->enum('status', ['active', 'unsubscribed', 'bounced', 'invalid'])->default('active');
            $table->string('source')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('email_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('email_contact_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('email_contacts')->cascadeOnDelete();
            $table->foreignId('list_id')->constrained('email_lists')->cascadeOnDelete();
            $table->timestamp('created_at')->nullable();
            $table->unique(['contact_id', 'list_id']);
        });

        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('provider_id')->constrained('email_providers')->restrictOnDelete();
            $table->string('subject');
            $table->string('preheader')->nullable();
            $table->longText('html_body');
            $table->longText('text_body')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'sending', 'paused', 'finished', 'canceled'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('email_campaign_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('email_campaigns')->cascadeOnDelete();
            $table->foreignId('list_id')->constrained('email_lists')->cascadeOnDelete();
            $table->timestamp('created_at')->nullable();
            $table->unique(['campaign_id', 'list_id']);
        });

        Schema::create('email_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->nullable()->constrained('email_campaigns')->nullOnDelete();
            $table->foreignId('provider_id')->constrained('email_providers')->restrictOnDelete();
            $table->foreignId('contact_id')->constrained('email_contacts')->restrictOnDelete();
            $table->string('tracking_token')->unique();
            $table->string('to_email');
            $table->string('to_name')->nullable();
            $table->string('subject');
            $table->longText('html_body');
            $table->longText('text_body')->nullable();
            $table->enum('status', ['pending', 'processing', 'sent', 'delivered', 'opened', 'clicked', 'bounced', 'complained', 'failed', 'canceled'])->default('pending');
            $table->string('provider_message_id')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('bounced_at')->nullable();
            $table->timestamp('complained_at')->nullable();
            $table->timestamps();
            $table->unique(['campaign_id', 'contact_id']);
            $table->index(['provider_id', 'status', 'created_at']);
        });

        Schema::create('email_unsubscribes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->nullable()->constrained('email_contacts')->nullOnDelete();
            $table->string('email');
            $table->foreignId('campaign_id')->nullable()->constrained('email_campaigns')->nullOnDelete();
            $table->text('reason')->nullable();
            $table->string('token')->unique();
            $table->timestamp('unsubscribed_at');
            $table->timestamps();
            $table->index('email');
        });

        Schema::create('email_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('email_messages')->cascadeOnDelete();
            $table->enum('event_type', ['sent', 'delivered', 'opened', 'clicked', 'bounced', 'complained', 'failed', 'unsubscribed']);
            $table->text('url')->nullable();
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->index(['message_id', 'event_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_events');
        Schema::dropIfExists('email_unsubscribes');
        Schema::dropIfExists('email_messages');
        Schema::dropIfExists('email_campaign_lists');
        Schema::dropIfExists('email_campaigns');
        Schema::dropIfExists('email_contact_list');
        Schema::dropIfExists('email_lists');
        Schema::dropIfExists('email_contacts');
        Schema::dropIfExists('email_providers');
    }
};
