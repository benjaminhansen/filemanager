<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use App\User;
use App\Support\Helpers;
use App\LdapAttribute;
use App\DepartmentPermissionGroup;

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

            $global_administrator_group = DepartmentPermissionGroup::where('department_id', '-1')->first()->ldap_group_dn;

            // do a check for the user in the local database

            $fname_obj = LdapAttribute::where('local_attribute', 'fname')->first();
            if($fname_obj && !is_null($fname_obj->ldap_attribute)) {
                $fname_attr = $fname_obj->ldap_attribute;
            } else {
                $fname_attr = "givenName";
            }

            $lname_obj = LdapAttribute::where('local_attribute', 'lname')->first();
            if($lname_obj && !is_null($lname_obj->ldap_attribute)) {
                $lname_attr = $lname_obj->ldap_attribute;
            } else {
                $lname_attr = "sn";
            }

            $email_obj = LdapAttribute::where('local_attribute', 'email')->first();
            if($email_obj && !is_null($email_obj->ldap_attribute)) {
                $email_attr = $email_obj->ldap_attribute;
            } else {
                $email_attr = "mail";
            }

            $username_obj = LdapAttribute::where('local_attribute', 'username')->first();
            if($username_obj && !is_null($username_obj->ldap_attribute)) {
                $username_attr = $username_obj->ldap_attribute;
            } else {
                $username_attr = "uid";
            }

            $groups_obj = LdapAttribute::where('local_attribute', 'groups')->first();
            if($groups_obj && !is_null($groups_obj->ldap_attribute)) {
                $groups_attr = $groups_obj->ldap_attribute;
            } else {
                $groups_attr = "memberOf";
            }

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
