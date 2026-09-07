<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Services\PortfolioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(
        protected PortfolioService $portfolioService
    ) {}

    public function dashboard(): View
    {
        $messages = $this->portfolioService->getLatestUnreadMessages();
        $unreadCount = $this->portfolioService->getUnreadMessagesCount();

        return view('admin.dashboard', compact('messages', 'unreadCount'));
    }

    public function messages(): View
    {
        $messages = $this->portfolioService->getMessages();

        return view('admin.messages.index', compact('messages'));
    }

    public function showMessage(Message $message): View
    {
        if (! $message->lu) {
            $message->update(['lu' => true]);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function markMessageAsRead(Message $message): RedirectResponse
    {
        $message->update(['lu' => true]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Le message a été marqué comme lu.');
    }

    public function profile()
    {
        $profile = $this->portfolioService->getProfile();

        return view('admin.profile', compact('profile'));
    }
}
