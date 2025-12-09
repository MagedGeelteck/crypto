<?php

namespace App\Providers;

use App\Models\Sell;
use App\Models\User;
use App\Lib\Searchable;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Constants\Status;
use App\Models\SupportTicket;
use App\Models\AdminNotification;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Builder::mixin(new Searchable);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set remember me token lifetime (5 years in minutes)
        \Illuminate\Support\Facades\Config::set('auth.remember_token_lifetime', config('auth.remember_token_lifetime', 2628000));
        
        // Force clearnet URL ONLY for email generation (not for web assets)
        // This allows .onion to load assets correctly while emails use clearnet URLs
        
        // Add event listener for all emails being sent to improve deliverability
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Mail\Events\MessageSending::class,
            function ($event) {
                // Add custom headers to improve deliverability and avoid spam
                $headers = $event->message->getHeaders();
                
                // Add X-Mailer header
                if (!$headers->has('X-Mailer')) {
                    $headers->addTextHeader('X-Mailer', 'Laravel Mailer');
                }
                
                // Add X-Priority header (normal priority)
                if (!$headers->has('X-Priority')) {
                    $headers->addTextHeader('X-Priority', '3');
                }
                
                // Add MIME-Version if not present
                if (!$headers->has('MIME-Version')) {
                    $headers->addTextHeader('MIME-Version', '1.0');
                }
                
                // Replace .onion URLs with clearnet URL in email body for better deliverability
                $body = $event->message->getBody();
                if ($body) {
                    if (is_string($body)) {
                        $body = str_replace('mtidtmncruzy4k3p5jhthhsmm3vohsxxb2vayjicntykoy4lwcl7gvqd.onion', '144.24.223.119', $body);
                        $event->message->setBody($body);
                    } elseif (method_exists($body, 'getBodyAsString')) {
                        $bodyString = $body->getBodyAsString();
                        $bodyString = str_replace('mtidtmncruzy4k3p5jhthhsmm3vohsxxb2vayjicntykoy4lwcl7gvqd.onion', '144.24.223.119', $bodyString);
                        $event->message->html($bodyString);
                    }
                }
            }
        );

        if (!cache()->get('SystemInstalled')) {
            $envFilePath = base_path('.env');
            if (!file_exists($envFilePath)) {
                header('Location: install');
                exit;
            }
            $envContents = file_get_contents($envFilePath);
            if (empty($envContents)) {
                header('Location: install');
                exit;
            } else {
                cache()->put('SystemInstalled', true);
            }
        }


        $activeTemplate = activeTemplate();
        $viewShare['activeTemplate'] = $activeTemplate;
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        $viewShare['emptyMessage'] = 'Data not found';
        view()->share($viewShare);


        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'bannedUsersCount'           => User::banned()->count(),
                'emailUnverifiedUsersCount'  => User::emailUnverified()->count(),
                'mobileUnverifiedUsersCount' => User::mobileUnverified()->count(),
                'pendingOrderCount'          => Sell::processing()->count(),
                'pendingTicketCount'         => SupportTicket::whereIN('status', [Status::TICKET_OPEN, Status::TICKET_REPLY])->count(),
                'pendingDepositsCount'       => Deposit::pending()->count(),
                'updateAvailable'            => version_compare(gs('available_version'),systemDetails()['version'],'>') ? 'v'.gs('available_version') : false,
            ]);
        });

        view()->composer('admin.partials.topnav', function ($view) {
            $view->with([
                'adminNotifications' => AdminNotification::where('is_read', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get(),
                'adminNotificationCount' => AdminNotification::where('is_read', Status::NO)->count(),
            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

        if (gs('force_ssl')) {
            \URL::forceScheme('https');
        }


        Paginator::useBootstrapFive();
    }
}
