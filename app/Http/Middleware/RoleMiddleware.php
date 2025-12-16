<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Symfony\Component\HttpFoundation\Response;

    class RoleMiddleware
    {
        /**
         * Handle an incoming request.
         */
        public function handle(Request $request, Closure $next, ...$roles): Response
        {
            if (!Auth::check()) {
                return redirect('/login');
            }

            $user = Auth::user();

            // Check if the user's role is in the list of required roles
            if (in_array($user->role, $roles)) {
                return $next($request);
            }
            
            // If the role doesn't match
            abort(403, 'Unauthorized action. Required role: ' . implode(', ', $roles));
        }
    }