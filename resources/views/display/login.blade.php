<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Login — {{ $customer->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f172a;
            font-family: ui-sans-serif, system-ui, sans-serif;
            color: #f1f5f9;
        }

        .card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.75rem;
            padding: 2.5rem;
            width: 100%;
            max-width: 24rem;
        }

        .card h1 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .card p {
            font-size: 0.875rem;
            color: #94a3b8;
            margin-bottom: 1.75rem;
        }

        .field {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.4rem;
            color: #cbd5e1;
        }

        input {
            width: 100%;
            padding: 0.6rem 0.75rem;
            background: #0f172a;
            border: 1px solid #475569;
            border-radius: 0.375rem;
            color: #f1f5f9;
            font-size: 0.9rem;
            outline: none;
        }

        input:focus { border-color: #6366f1; }

        .error {
            color: #f87171;
            font-size: 0.8rem;
            margin-top: 0.35rem;
        }

        button {
            width: 100%;
            padding: 0.65rem;
            background: #6366f1;
            border: none;
            border-radius: 0.375rem;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            margin-top: 0.5rem;
        }

        button:hover { background: #4f46e5; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Display Access</h1>
        <p>{{ $customer->name }} — enter your CMS credentials</p>

        <form method="POST" action="{{ route('display.auth', ['customer' => $customerId]) }}{{ $slideshowId ? '?slideshow='.$slideshowId : '' }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    autofocus
                    required
                >
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    required
                >
                @error('password')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit">Sign in</button>
        </form>
    </div>
</body>
</html>
