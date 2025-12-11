<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(\App\Http\Requests\ProfileUpdateRequest $request): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        // dump request for debugging (logged)
        \Log::debug('PROFILE DEBUG - request all', ['all' => $request->all(), 'hasFile' => $request->hasFile('profile_picture')]);

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            \Log::debug('PROFILE DEBUG - uploaded file', [
                'isValid' => $file->isValid(),
                'error' => $file->getError(),
                'origName' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);

            if ($file->isValid()) {
                $path = $file->store('profile_pictures', 'public');
                \Log::debug('PROFILE DEBUG - stored path', ['path' => $path]);

                // delete old
                if (! empty($user->profile_picture)) {
                    \Storage::disk('public')->delete($user->profile_picture);
                }

                $user->profile_picture = $path;
            }
        }

        $data = $request->validated();
        if (isset($data['first_name'])) $user->first_name = $data['first_name'];
        if (isset($data['last_name']))  $user->last_name  = $data['last_name'];
        if (isset($data['email']))      $user->email      = $data['email'];

        $saved = $user->save();
        \Log::debug('PROFILE DEBUG - save result', [
            'saved' => $saved,
            'user_id' => $user->id,
            'db_profile_picture' => \DB::table('users')->where('id', $user->id)->value('profile_picture'),
            'attributes' => $user->getAttributes(),
        ]);

        return back()->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
