<?php

namespace App\Policies;

use App\Models\Option;
use App\Models\Poll;
use App\Models\User;

class OptionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Option $option): bool
    {
        return true;
    }

    public function create(User $user, Poll $poll): bool
    {
        return $user->isAdmin() && $poll->user_id === $user->id;
    }

    public function update(User $user, Option $option): bool
    {
        return $user->isAdmin() && $option->poll->user_id === $user->id;
    }

    public function delete(User $user, Option $option): bool
    {
        return $user->isAdmin() && $option->poll->user_id === $user->id;
    }

    public function restore(User $user, Option $option): bool
    {
        return $this->delete($user, $option);
    }

    public function forceDelete(User $user, Option $option): bool
    {
        return $this->delete($user, $option);
    }
}
