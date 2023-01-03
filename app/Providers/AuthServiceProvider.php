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
            $count = $user->withCount('questions')->first();

            return $count->questions_count >= 3
                ? Response::allow()
                : Response::deny('You must ask 3 questions first before you can vote.');
        });

        Gate::define('users-edit', function (User $user, $id) {
            return $user->id === $id;
        });

        Gate::define('users-allowed', function (User $user, $user_id) {
            return $user->id === auth()->id()
                && $user->id === $user_id;
        });
    }
}
