<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CustomerQuestion;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class UserSupportForm extends Component
{
    #[Validate('required|string|max:255|min:3')]
    public $title = '';

    #[Validate('required|string|min:10|max:2000')]
    public $description = '';

    public function save()
    {
        if (!Auth::check()) {
            Notification::make()
                ->title('Please Login before submitting a form')
                ->warning()
                ->send();

            return $this->redirect(route('filament.app.auth.login'));
        }


        $lastQuestion = CustomerQuestion::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastQuestion && $lastQuestion->created_at->diffInMinutes() < 60) {
            Notification::make()
                ->title('You can only submit 1 request for every 60 minutes')
                ->warning()
                ->send();

            return $this->redirect(route('filament.app.pages.dashboard'));
        }


        $this->validate();

        $request = new CustomerQuestion();
        $request->title = $this->title;
        $request->description = $this->description;
        $request->user_id = Auth::id();
        $request->save();

        Notification::make()
            ->title('Request submitted successfully!')
            ->success()
            ->send();

        return $this->redirect(route('filament.app.pages.dashboard'));
    }

    public function render()
    {
        return view('livewire.user-support-form');
    }
}
