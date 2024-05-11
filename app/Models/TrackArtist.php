<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackArtist extends Model {
    use HasFactory;

    const ROLE_PRIMARY_ARTIST = 1;
    const ROLE_FEATURING_ARTIST = 2;
    const ROLE_REMIXER = 3;

    public function showRole(int $role = null): string {
        if (!$role) {
            $role = $this->role;
        }

        $role_text = "";
        switch ($role) {
            case self::ROLE_PRIMARY_ARTIST:
                $role_text =  'Primary Artist';
                break;
            case self::ROLE_FEATURING_ARTIST:
                $role_text = 'Featuring Artist';
                break;
            case self::ROLE_REMIXER:
                $role_text = 'Remixer';
                break;
            default:
                $role_text =  'Primary Artist';
                break;
        }

        return $role_text;
    }

    public function getRoleOptions(): array {
        return [
            self::ROLE_PRIMARY_ARTIST => $this->showRole(self::ROLE_PRIMARY_ARTIST),
            self::ROLE_FEATURING_ARTIST => $this->showRole(self::ROLE_FEATURING_ARTIST),
            self::ROLE_REMIXER => $this->showRole(self::ROLE_REMIXER),
        ];
    }

    /**
     * Get the artist
     */
    public function artist(): BelongsTo {
        return $this->belongsTo(Artist::class);
    }
}
