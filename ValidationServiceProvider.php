<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('sum', function ($attribute, $value, $parameters, $validator) {
            if (count($parameters) !== 1) {
                throw new InvalidArgumentException('A soma da regra de validação requer exatamente 1 parâmetro.');
            }

            $validator->addReplacer('sum', function($message, $attribute, $rule, $parameters){
                return str_replace([':percentage'], $parameters, $message);
            });

            
            return array_sum($value) >= $parameters[0];
        }, 'A soma dos campos :attribute deve totalizar :percentage%');

        Validator::extend('not_exists', function($attribute, $value, $parameters)
        {
            return DB::table($parameters[0])
                ->where($parameters[1], '=', $value)
                ->count()<1;
        }, "O :attribute já contém um cadastro");
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
