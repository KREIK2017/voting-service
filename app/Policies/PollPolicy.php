<?php

namespace App\Policies;

use App\Models\Poll;
use App\Models\User;

class PollPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Poll $poll): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Poll $poll): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Poll $poll): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Poll $poll): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Poll $poll): bool
    {
        return $user->isAdmin();
    }
}
