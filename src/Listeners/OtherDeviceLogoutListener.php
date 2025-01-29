<?php

namespace AuroraWebSoftware\LaravelAuthenticationLog\Listeners;

use Illuminate\Auth\Events\OtherDeviceLogout;
use Illuminate\Http\Request;
use AuroraWebSoftware\LaravelAuthenticationLog\Models\AuthenticationLog;
use AuroraWebSoftware\LaravelAuthenticationLog\Traits\AuthenticationLoggable;

class OtherDeviceLogoutListener
{
    public Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle($event): void
    {
        $listener = config('authentication-log.events.other-device-logout', OtherDeviceLogout::class);

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
            $authenticationLog = $user->authentications()->whereIpAddress($ip)->whereUserAgent($userAgent)->first();

            if (! $authenticationLog) {
                $authenticationLog = new AuthenticationLog([
                    'ip_address' => $ip,
                    'server_ip_address' => $serverIp,
                    'user_agent' => $userAgent,
                ]);
            }

            foreach ($user->authentications()->whereLoginSuccessful(true)->whereNull('logout_at')->get() as $log) {
                if ($log->id !== $authenticationLog->id) {
                    $log->update([
                        'cleared_by_user' => true,
                        'logout_at' => now(),
                    ]);
                }
            }
        }
    }
}
