<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    public function delete(User $user, Document $document): bool
    {
        if ($user->is_admin) {
            return true;
        }

        if ($user->can_manage_documents) {
            return true;
        }

        return $document->user_id !== null && $document->user_id === $user->id;
    }
}

