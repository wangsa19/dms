<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
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
        return $user->can('view documents');
    }

    public function view(User $user, Document $document): bool
    {
        return $user->can('view documents');
    }

    public function create(User $user): bool
    {
        return $user->can('create documents');
    }

    public function update(User $user, Document $document): bool
    {
        if (!$user->can('edit documents')) {
            return false;
        }

        // Bisa edit jika dia adalah owner (PIC) yang ditunjuk
        if ($user->employee && $user->employee->id === $document->owner_id) {
            return true;
        }

        // ATAU jika dia adalah pengupload asli (creator pertama dokumen)
        $firstVersion = $document->versions()->oldest('version_number')->first();
        if ($firstVersion && $firstVersion->uploader_id === $user->id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Document $document): bool
    {
        if (!$user->can('delete documents')) {
            return false;
        }

        if ($user->employee && $user->employee->id === $document->owner_id) {
            return true;
        }

        $firstVersion = $document->versions()->oldest('version_number')->first();
        if ($firstVersion && $firstVersion->uploader_id === $user->id) {
            return true;
        }

        return false;
    }
}
