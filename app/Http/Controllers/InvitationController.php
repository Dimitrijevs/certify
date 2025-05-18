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
                ->title(__('invitation.user_joined_another_institution'))
                ->body(__('invitation.user') . ' ' . $user->name . ' ' . __('invitation.has_joined_another_institution'))
                ->info()
                ->sendToDatabase($receiver);
        }

        $user->school_id = $institution->id;
        $user->group_id = $group->id;

        if ($is_teacher) {
            $user->role_id = 3;

            $title = __('invitation.you_accepted_invitation_to_join_group_as_teacher');
            $description = __('invitation.you_have_successfully_accepted_invitation_to_join_group_as_teacher');

            $ownerTitle = __('invitation.new_teacher_joined');
            $ownerDescription = __('invitation.user') . ' ' . $user->name . ' ' . __('invitation.has_joined_the_group') . ': ' . $group->name . ' ' . __('invitation.as_a_teacher');
        } else {
            $user->role_id = 4;

            $title = __('invitation.you_accepted_invitation_to_join_group_as_student');
            $description = __('invitation.you_have_successfully_accepted_invitation_to_join_group_as_student');

            $ownerTitle = __('invitation.new_student_joined');
            $ownerDescription = __('invitation.user') . ' ' . $user->name . ' ' . __('invitation.has_joined_the_group') . ': ' . $group->name . ' ' . __('invitation.as_a_student');
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
            $title = __('invitation.invitation_rejected_teacher');
            $body = __('invitation.you_rejected_invitation_to_join_group_as_teacher');
            $ownerBody = __('invitation.user') . ' ' . $user->name . ' ' . __('invitation.has_rejected_the_invitation') . ': '
                . $group->name . ' ' . __('invitation.as_a_teacher');
        } else {
            $title = __('invitation.invitation_rejected_student');
            $body = __('invitation.you_rejected_invitation_to_join_group_as_student');
            $ownerBody = __('invitation.user') . ' ' . $user->name . ' ' . __('invitation.has_rejected_the_invitation') . ': '
                . $group->name . ' ' . __('invitation.as_a_student');
        }

        $reciever = User::find($institution->created_by);

        Notification::make()
            ->title($title)
            ->body($body)
            ->success()
            ->send();

        Notification::make()
            ->title(__('invitation.invitation_rejected'))
            ->body($ownerBody)
            ->success()
            ->sendToDatabase($reciever);

        return redirect()->back();
    }
}
