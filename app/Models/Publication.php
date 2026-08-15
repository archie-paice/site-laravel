<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Publication extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'description', 'publication_category_id', 'original_filename'])
            ->logOnlyDirty();
    }

    protected $fillable = [
        'publication_category_id',
        'name',
        'description',
        'file_path',
        'original_filename',
        'file_size',
    ];

    protected $appends = ['file_url'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PublicationCategory::class, 'publication_category_id');
    }

    protected function fileUrl(): Attribute
    {
        return Attribute::get(fn () => $this->file_path
            ? route('publications.file', $this)
            : null);
    }

    /**
     * Whether a browser renders this file in a tab rather than saving it.
     *
     * This drives both the Content-Disposition the file route sends and the
     * action label shown in the listing, so the two can never disagree about
     * what clicking a document will do.
     */
    public function opensInBrowser(): bool
    {
        $extension = strtolower(pathinfo(
            $this->original_filename ?? $this->file_path,
            PATHINFO_EXTENSION
        ));

        return in_array($extension, ['pdf', 'png', 'jpg', 'jpeg']);
    }
}
