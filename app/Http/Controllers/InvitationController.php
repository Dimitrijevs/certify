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

        $is_teacher = $request->is_teacher;

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

        if ($is_teacher) {
            $user->role_id = 3;

            $title = 'You had accepted the invitation to join the group as a teacher';
            $description = 'You have successfully accepted the invitation to join the group as a teacher';

            $ownerTitle = 'New Teacher Joined';
            $ownerDescription = 'User ' . $user->name . ' has joined the group: ' . $group->name . ' as a teacher';
        } else {
            $user->role_id = 4;

            $title = 'You had accepted the invitation to join the group as a student.';
            $description = 'You have successfully accepted the invitation to join the group as a student';

            $ownerTitle = 'New Student Joined';
            $ownerDescription = 'User ' . $user->name . ' has joined the group: ' . $group->name . ' as a student';
        }

        $user->save();

        Notification::make()
            ->title($title)
            ->body($description)
            ->success()
            ->send();

        $institutionCreator = User::find($institution->created_by);

        Notification::make()
            ->title($ownerTitle)
            ->body($ownerDescription)
            ->success()
            ->sendToDatabase($institutionCreator);

        return redirect()->back();
    }

    public function rejectInvite(Request $request, School $institution, Group $group)
    {
        $user = User::find(Auth::id());
        $isTeacher = $request->is_teacher;

        if ($isTeacher) {
            $title = 'Invitation Rejected (Teacher)';
            $body = 'You have successfully rejected the invitation to join the group as a teacher.';
            $ownerBody = 'User ' . $user->name . ' has rejected the invitation to join the group: '
                . $group->name . ' as a teacher.';
        } else {
            $title = 'Invitation Rejected (Student)';
            $body = 'You have successfully rejected the invitation to join the group as a student.';
            $ownerBody = 'User ' . $user->name . ' has rejected the invitation to join the group: '
                . $group->name . ' as a student.';
        }

        $reciever = User::find($institution->created_by);

        Notification::make()
            ->title($title)
            ->body($body)
            ->success()
            ->send();

        Notification::make()
            ->title('Invitation Rejected')
            ->body($ownerBody)
            ->success()
            ->sendToDatabase($reciever);

        return redirect()->back();
    }
}
