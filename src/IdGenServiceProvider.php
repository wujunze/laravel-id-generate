<?php

namespace Wujunze\IdGen;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class IdGenServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('uuid', function ($attribute, $value, $parameters, $validator) {
            return IdGen::validate($value);
        });

        Validator::extend('gen_id', function ($attribute, $value, $parameters, $validator) {
            return IdGen::IdValidate($value);
        });
    }
}
