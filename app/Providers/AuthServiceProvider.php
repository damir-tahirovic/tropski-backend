<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Extra;
use App\Models\MainCategory;
use App\Policies\ExtraPolicy;
use App\Policies\MainCategoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Extra::class => ExtraPolicy::class,
        MainCategory::class => MainCategoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
