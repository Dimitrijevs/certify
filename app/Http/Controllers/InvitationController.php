<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class InvitationController extends Controller
{
    public function acceptInvite(Request $request, School $institution, Group $group)
    {
        $user = User::find(Auth::id());

        if ($user->school_id && $user->school_id != $institution->id) {
            $receiver = User::find($user->school->created_by);

            Notification::make()
                ->title('User Joined Another Institution')
                ->body('User ' . $user->name . ' has joined another institution.')
                ->info()
                ->sendToDatabase($receiver);
        }

        $user->school_id = $institution->id;
        $user->group_id = $group->id;
        $user->save();

        Notification::make()
            ->title('Invitation Accepted')
            ->body('You have successfully accepted the invitation to join the group.')
            ->success()
            ->send();

        $institutionCreator = User::find($institution->created_by);

        Notification::make()
            ->title('New Member Joined')
            ->body('User ' . $user->name . ' has joined the group: ' . $group->name)
            ->success()
            ->sendToDatabase($institutionCreator);

        return redirect()->back();
    }

    public function rejectInvite(Request $request, School $institution, Group $group)
    {
        $user = User::find(Auth::id());

        Notification::make()
            ->title('Invitation Rejected')
            ->body('You have successfully rejected the invitation to join the group.')
            ->success()
            ->send();

        Notification::make()
            ->title('Invitation Rejected')
            ->body('User ' . $user->name . ' has rejected the invitation to join the group: ' . $group->name)
            ->success()
            ->sendToDatabase($institution->created_by);

        return redirect()->back();
    }
}
