<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserAuditController extends Controller
{
    /**
     * Return paginated audits for the given user model.
     */
    public function index(Request $request, User $user)
    {
        $this->authorize('view-audit-history');

        $perPage = (int) $request->query('per_page', 10);

        $paginator = $user->audits()->with('user')->latest()->paginate($perPage);

        // Transform items to decode json fields and include user summary
        $data = $paginator->through(function ($audit) {
            return [
                'id' => $audit->id,
                'event' => $audit->event,
                'created_at' => $audit->created_at?->toDateTimeString(),
                'old_values' => is_string($audit->old_values) ? json_decode($audit->old_values, true) ?? [] : ($audit->old_values ?? []),
                'new_values' => is_string($audit->new_values) ? json_decode($audit->new_values, true) ?? [] : ($audit->new_values ?? []),
                'user' => $audit->user ? ['id' => $audit->user->id, 'name' => $audit->user->name ?? null, 'email' => $audit->user->email ?? null] : null,
                'user_id' => $audit->user_id,
            ];
        });

        // Return paginator-like structure
        return response()->json([
            'data' => $data->items(),
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'next_page_url' => $paginator->nextPageUrl(),
            'prev_page_url' => $paginator->previousPageUrl(),
        ]);
    }
}
