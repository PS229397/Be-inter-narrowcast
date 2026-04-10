<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\CustomComponent;
use App\Models\Location;
use App\Models\Slide;
use App\Models\Slideshow;
use App\Models\User;
use App\Scopes\CustomerScope;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApplyCustomerScope
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $customerId = Auth::guard('web')->user()?->customer_id;

        foreach ($this->models() as $model) {
            $model::addGlobalScope('panel_customer_scope', new CustomerScope($customerId));
        }

        return $next($request);
    }

    /**
     * @return array<class-string>
     */
    protected function models(): array
    {
        return [
            User::class,
            Category::class,
            Location::class,
            Slide::class,
            Slideshow::class,
            CustomComponent::class,
        ];
    }
}

