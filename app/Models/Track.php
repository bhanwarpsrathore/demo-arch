<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterByTeam;

class Track extends Model {
    use FilterByTeam,
        HasFactory;

    const TYPE_COVER = 1;
    const TYPE_ORIGINAL = 2;

    const EXPLICIT_YES = 1;
    const EXPLICIT_NO = 0;

    /**
     * Show the track type
     */
    public function showType(int $type = null): string {
        if (!$type) {
            $type = $this->type;
        }

        $type_text = "";
        switch ($type) {
            case self::TYPE_COVER:
                $type_text = "Cover";
                break;
            case self::TYPE_ORIGINAL:
                $type_text = "Original";
                break;
            default:
                $type_text = "--";
                break;
        }

        return $type_text;
    }

    /**
     * Get all artists names, comma separated
     */
    public function getArtistNames(): string {
        $names = [];
        $artists = $this->artists()->orderBy('sort', 'ASC')->get();
        foreach ($artists as $artist) {
            $first_artist = $artist->artist()->first(['artist_name']);
            if (isset($first_artist->artist_name)) {
                $names[] = $first_artist->artist_name;
            }
        }

        return implode(", ", $names);
    }

    /**
     * Get the artists for the track.
     */
    public function artists(): HasMany {
        return $this->hasMany(TrackArtist::class);
    }
}
