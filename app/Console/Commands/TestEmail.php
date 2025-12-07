<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info('Testing email configuration...');
        $this->info('Sending test email to: ' . $email);
        
        try {
            Mail::raw('This is a test email from your crypto marketplace. If you received this, your email configuration is working correctly!', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - Email Configuration Working');
            });
            
            $this->info('✅ Email sent successfully!');
            $this->info('Check your inbox at: ' . $email);
            $this->info('');
            $this->warn('⚠️  If you don\'t receive the email, check:');
            $this->line('1. MAIL_PASSWORD is set in .env file');
            $this->line('2. Gmail App Password is correct (not regular password)');
            $this->line('3. Check spam/junk folder');
            $this->line('4. Verify MAIL_FROM_ADDRESS matches MAIL_USERNAME');
            
        } catch (\Exception $e) {
            $this->error('❌ Email failed to send!');
            $this->error('Error: ' . $e->getMessage());
            $this->info('');
            $this->warn('Common fixes:');
            $this->line('1. Set MAIL_PASSWORD in .env file');
            $this->line('2. Generate Gmail App Password: https://myaccount.google.com/apppasswords');
            $this->line('3. Run: php artisan config:clear');
        }
        
        return 0;
    }
}
