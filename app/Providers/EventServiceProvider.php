<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use App\User;
use App\Support\Helpers;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Event::listen('Aacotroneo\Saml2\Events\Saml2LoginEvent', function(Saml2LoginEvent $event) {
            $messageId = $event->getSaml2Auth()->getLastMessageId();
            $user = $event->getSaml2User();
            $userData = [
                'id' => $user->getUserId(),
                'attributes' => $user->getAttributes(),
                'assertion' => $user->getRawSamlAssertion()
            ];

            dd($user);

            $global_administrator_group = env('GLOBAL_ADMINS_GROUP');

            // do a check for the user in the local database

            $fname_attr = env('LDAP_ATTR_FNAME', 'givenName');
            $lname_attr = env('LDAP_ATTR_LNAME', 'sn');
            $email_attr = env('LDAP_ATTR_EMAIL', 'mail');
            $username_attr = env('LDAP_ATTR_USERNAME', 'uid');
            $groups_attr = env('LDAP_ATTR_GROUPS', 'memberOf');

            $fname = $userData['attributes'][$fname_attr][0];
            $lname = $userData['attributes'][$lname_attr][0];
            $email = $userData['attributes'][$email_attr][0];
            $username = $userData['attributes'][$username_attr][0];
            $groups = $userData['attributes'][$groups_attr];

            $user_found = User::where('username', $username)->first();
            if($user_found) {
                $user_found->fname = $fname;
                $user_found->lname = $lname;
                $user_found->email = $email;
                $user_found->save();

                if(in_array($global_administrator_group, $groups)) {
                    Helpers::auditGlobalAdmin($user_found, $global_administrator_group);
                } else {
                    Helpers::auditGroups($user_found, $groups);
                }

                auth()->login($user_found);
                return redirect()->intended('/');
            } else {
                $new_user = new User;
                $new_user->fname = $fname;
                $new_user->lname = $lname;
                $new_user->email = $email;
                $new_user->username = $username;
                $new_user->save();

                if(in_array($global_administrator_group, $groups)) {
                    Helpers::auditGlobalAdmin($user_found, $global_administrator_group);
                } else {
                    Helpers::auditGroups($user_found, $groups);
                }

                auth()->login($new_user);
                return redirect()->intended('/');
            }
        });
    }
}
