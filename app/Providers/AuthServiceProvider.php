<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Question;
use App\Models\User;
use App\Policies\QuestionPolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Question::class => QuestionPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('users-vote', function (User $user) {
            $user->withCount('questions')->first();

            return $user->questions_count >= 3
                ? Response::allow()
                : Response::deny('You must ask 3 questions first before you can vote.');
        });
    }
}
