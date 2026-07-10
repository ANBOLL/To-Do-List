<?php

namespace App\Providers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->app->afterResolving(
			JsonResponse::class,
			fn(JsonResponse $json) => $json->setEncodingOptions(JSON_UNESCAPED_UNICODE)
		);
	}

	public function boot(): void
	{
		//
	}
}
