<?php

namespace App\Policies;

use App\Models\License;
use App\Models\User;

class LicensePolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view licenses');
    }

    public function view(User $user, License $license): bool
    {
        return $user->can('view licenses');
    }

    public function create(User $user): bool
    {
        return $user->can('create licenses');
    }

    public function update(User $user, License $license): bool
    {
        if (!$user->can('edit licenses')) {
            return false;
        }

        if ($user->employee && $user->employee->id === $license->owner_id) {
            return true;
        }

        $firstVersion = $license->versions()->oldest('version_number')->first();
        if ($firstVersion && $firstVersion->uploader_id === $user->id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, License $license): bool
    {
        if (!$user->can('delete licenses')) {
            return false;
        }

        if ($user->employee && $user->employee->id === $license->owner_id) {
            return true;
        }

        $firstVersion = $license->versions()->oldest('version_number')->first();
        if ($firstVersion && $firstVersion->uploader_id === $user->id) {
            return true;
        }

        return false;
    }
}
