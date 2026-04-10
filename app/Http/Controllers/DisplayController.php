<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DisplayToken;
use App\Models\Slideshow;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DisplayController extends Controller
{
    public function show(Request $request, int $customerId, int $slideshowId): View|RedirectResponse
    {
        if (! $this->validateToken($request, $customerId)) {
            return redirect()->route('display.login', [
                'customer' => $customerId,
                'slideshow' => $slideshowId,
            ]);
        }

        $slideshow = Slideshow::where('customer_id', $customerId)->findOrFail($slideshowId);

        $orientation = $slideshow->locations()->first()?->orientation?->value ?? 'landscape';

        return view('display', compact('slideshow', 'customerId', 'slideshowId', 'orientation'));
    }

    public function loginForm(Request $request, int $customerId): View
    {
        $customer = Customer::findOrFail($customerId);

        return view('display.login', [
            'customer' => $customer,
            'customerId' => $customerId,
            'slideshowId' => $request->query('slideshow'),
        ]);
    }

    public function login(Request $request, int $customerId): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        Customer::findOrFail($customerId);

        $user = User::where('customer_id', $customerId)
            ->where('email', $request->email)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['email' => 'These credentials do not match our records.'])
                ->withInput($request->only('email'));
        }

        $raw = Str::random(64);

        DisplayToken::create([
            'customer_id' => $customerId,
            'token' => hash('sha256', $raw),
            'name' => $request->userAgent(),
            'expires_at' => now()->addYear(),
        ]);

        $slideshowId = $request->query('slideshow');
        $redirect = $slideshowId
            ? route('display.show', ['customer' => $customerId, 'slideshow' => $slideshowId])
            : url('/display/' . $customerId);

        return redirect($redirect)->cookie(
            'display_token_' . $customerId,
            $raw,
            60 * 24 * 365,
            '/',
            null,
            $request->isSecure(),
            true,
        );
    }

    public function slides(Request $request, int $customerId, int $slideshowId): JsonResponse
    {
        if (! $this->validateToken($request, $customerId)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $slideshow = Slideshow::where('customer_id', $customerId)->findOrFail($slideshowId);

        $slides = $slideshow->slides()
            ->with('layout')
            ->orderBy('slideshow_slides.sort_order')
            ->get()
            ->filter(fn ($slide) => $slide->is_active
                && (! $slide->start_date || $slide->start_date->lte(today()))
                && (! $slide->end_date || $slide->end_date->gte(today()))
            )
            ->map(fn ($slide) => [
                'id' => $slide->id,
                'title' => $slide->title,
                'duration_in_seconds' => $slide->duration_in_seconds,
                'layout' => $slide->layout ? [
                    'grid' => $slide->layout->grid,
                    'orientation' => $slide->layout->orientation->value,
                ] : null,
                'slide_content' => $slide->slide_content ?? [],
            ])
            ->values();

        return response()->json(['slides' => $slides]);
    }

    private function validateToken(Request $request, int $customerId): bool
    {
        $raw = $request->cookie('display_token_' . $customerId);

        if (! $raw) {
            return false;
        }

        $hashed = hash('sha256', $raw);

        $token = DisplayToken::where('customer_id', $customerId)
            ->where('token', $hashed)
            ->where('expires_at', '>', now())
            ->first();

        if (! $token) {
            return false;
        }

        $token->update(['last_used_at' => now()]);

        return true;
    }
}
