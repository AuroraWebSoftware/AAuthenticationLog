<?php

namespace AuroraWebSoftware\LaravelAuthenticationLog;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\OtherDeviceLogout;
use Illuminate\Contracts\Events\Dispatcher;
use AuroraWebSoftware\LaravelAuthenticationLog\Commands\PurgeAuthenticationLogCommand;
use AuroraWebSoftware\LaravelAuthenticationLog\Listeners\FailedLoginListener;
use AuroraWebSoftware\LaravelAuthenticationLog\Listeners\LoginListener;
use AuroraWebSoftware\LaravelAuthenticationLog\Listeners\LogoutListener;
use AuroraWebSoftware\LaravelAuthenticationLog\Listeners\OtherDeviceLogoutListener;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelAuthenticationLogServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-authentication-log')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews()
            ->hasMigration('create_authentication_log_table')
            ->hasCommand(PurgeAuthenticationLogCommand::class);

        $events = $this->app->make(Dispatcher::class);
        $events->listen(config('authentication-log.events.login', Login::class), config('authentication-log.listeners.login', LoginListener::class));
        $events->listen(config('authentication-log.events.failed', Failed::class), config('authentication-log.listeners.failed', FailedLoginListener::class));
        $events->listen(config('authentication-log.events.logout', Logout::class), config('authentication-log.listeners.logout', LogoutListener::class));
        $events->listen(config('authentication-log.events.other-device-logout', OtherDeviceLogout::class), config('authentication-log.listeners.other-device-logout', OtherDeviceLogoutListener::class));
    }
}
