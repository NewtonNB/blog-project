<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TestEmailVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email-verification {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email verification setup and send a test verification email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testing Email Verification Setup...');
        $this->newLine();

        // Check mail configuration
        $this->info('📧 Mail Configuration:');
        $this->line('  Mailer: ' . config('mail.default'));
        $this->line('  Host: ' . config('mail.mailers.smtp.host'));
        $this->line('  Port: ' . config('mail.mailers.smtp.port'));
        $this->line('  Username: ' . (config('mail.mailers.smtp.username') ?: 'Not set'));
        $this->line('  From: ' . config('mail.from.address'));
        $this->newLine();

        // Check if credentials are set
        if (!config('mail.mailers.smtp.username') || !config('mail.mailers.smtp.password')) {
            $this->error('❌ Mail credentials not configured!');
            $this->warn('Please update your .env file with Mailtrap credentials:');
            $this->line('  MAIL_USERNAME=your_mailtrap_username');
            $this->line('  MAIL_PASSWORD=your_mailtrap_password');
            return 1;
        }

        $this->info('✅ Mail configuration looks good!');
        $this->newLine();

        // Get email from argument or ask
        $email = $this->argument('email');
        
        if (!$email) {
            $email = $this->ask('Enter email address to test (or press Enter to skip sending test email)');
        }

        if ($email) {
            // Find or create test user
            $user = User::where('email', $email)->first();

            if (!$user) {
                $this->warn("User with email {$email} not found.");
                
                if ($this->confirm('Would you like to create a test user?', true)) {
                    $name = $this->ask('Enter name', 'Test User');
                    
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => bcrypt('password123'),
                    ]);

                    $this->info("✅ Test user created!");
                    $this->line("  Email: {$email}");
                    $this->line("  Password: password123");
                    $this->newLine();
                }
            }

            if ($user) {
                // Check verification status
                if ($user->hasVerifiedEmail()) {
                    $this->warn("⚠️  User email is already verified.");
                    
                    if ($this->confirm('Reset verification status and send new email?', true)) {
                        $user->email_verified_at = null;
                        $user->save();
                        $this->info('✅ Verification status reset.');
                    } else {
                        return 0;
                    }
                }

                // Send verification email
                $this->info('📤 Sending verification email...');
                
                try {
                    $user->sendEmailVerificationNotification();
                    
                    $this->newLine();
                    $this->info('✅ Verification email sent successfully!');
                    $this->newLine();
                    $this->line('📬 Check your Mailtrap inbox at: https://mailtrap.io/inboxes');
                    $this->newLine();
                    $this->info('Next steps:');
                    $this->line('  1. Open your Mailtrap inbox');
                    $this->line('  2. Find the verification email');
                    $this->line('  3. Click the "Verify Email Address" button');
                    $this->line('  4. Try logging in with:');
                    $this->line("     Email: {$email}");
                    $this->line('     Password: password123');
                    
                } catch (\Exception $e) {
                    $this->error('❌ Failed to send email!');
                    $this->error('Error: ' . $e->getMessage());
                    $this->newLine();
                    $this->warn('Troubleshooting:');
                    $this->line('  1. Check your Mailtrap credentials in .env');
                    $this->line('  2. Run: php artisan config:clear');
                    $this->line('  3. Check logs: tail -f storage/logs/laravel.log');
                    return 1;
                }
            }
        }

        $this->newLine();
        $this->info('🎉 Email verification test complete!');
        
        return 0;
    }
}
