<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskActionLog;
use App\Models\Task;

class RedirectIfAuthenticatedWithRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
 public function handle(Request $request, Closure $next)
{
    if (Auth::check()) {
        $user = Auth::user();

        if ($user->roles->first()->name === "Brigadir") {
            // Якщо вже на сторінці перегляду завдання — не перенаправляти
            if ($request->routeIs('tasks.show')) {
                return $next($request);
            }

            $tasks = Task::whereHas('users', function ($query) {
                $query->where('role', 'brigadir')
                      ->where('user_id', Auth::id());
            })->get();

            foreach ($tasks as $task) {
                $lastAction = TaskActionLog::where('task_id', $task->id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($lastAction && $lastAction->action_type === "start") {
                    return redirect()->route('tasks.show', $lastAction->task_id)
                        ->with('message', 'Ви вже почали виконання цього завдання.');
                }
            }
        } else {
            return redirect()->route('home')
                ->with('message', 'Ви не маєте доступу до цієї сторінки.');
        }
    }

    return $next($request);
}

}
