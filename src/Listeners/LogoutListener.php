<?php

namespace AuroraWebSoftware\LaravelAuthenticationLog\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use AuroraWebSoftware\LaravelAuthenticationLog\Models\AuthenticationLog;
use AuroraWebSoftware\LaravelAuthenticationLog\Traits\AuthenticationLoggable;

class LogoutListener
{
    public Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle($event): void
    {
        $listener = config('authentication-log.events.logout', Logout::class);

        if (! $event instanceof $listener) {
            return;
        }

        if ($event->user) {
            if(! in_array(AuthenticationLoggable::class, class_uses_recursive(get_class($event->user)))) {
                return;
            }

            $user = $event->user;

            if (config('authentication-log.behind_cdn')) {
                $ip = $this->request->server(config('authentication-log.behind_cdn.http_header_field'));
            } else {
                $ip = $this->request->ip();
            }

            $serverIp = $this->request->server('SERVER_ADDR') ?? gethostbyname(gethostname());

            $userAgent = $this->request->userAgent();
            $log = $user->authentications()->whereIpAddress($ip)->whereUserAgent($userAgent)->orderByDesc('login_at')->first();

            if (! $log) {
                $log = new AuthenticationLog([
                    'ip_address' => $ip,
                    'server_ip_address' => $serverIp,
                    'user_agent' => $userAgent,
                ]);
            }

            $log->logout_at = now();

            $user->authentications()->save($log);
        }
    }
}
