<?php

namespace App\Http\Controllers;

use App\Models\LiveComment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LivePrayerController extends Controller
{
    public function index(): View
    {
        $liveComments = LiveComment::with('user')->latest()->take(24)->get();

        return view('live', [
            'liveComments' => $liveComments,
            'liveCommentCount' => LiveComment::count(),
            'activeMemberCount' => User::where('is_member', true)->count(),
            'latestComment' => $liveComments->first(),
        ]);
    }

    public function storeComment(Request $request): RedirectResponse
    {
        abort_unless($request->user() && ! $request->user()->is_admin, 403);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        LiveComment::create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return redirect()
            ->route('live')
            ->with('status', 'Your prayer comment has been added to the testimony wall.');
    }
}
