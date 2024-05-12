<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'alias',
        'filename',
        'file_path_original',
        'file_path_drawed_grid',
        'width',
        'height',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    /**
     * Returns TRUE if the template is used by the given $banner_id.
     */
    public function isUsedByBannerId($banner_id): bool
    {
        return (BannerTemplate::where(['banner_id' => $banner_id, 'template_id' => $this->id])->first()) ? true : false;
    }

    /**
     * Returns TRUE if the template is enabled for the given $banner_id.
     */
    public function isEnabledForBannerId($banner_id): bool
    {
        $banner_template = BannerTemplate::where(['banner_id' => $banner_id, 'template_id' => $this->id])->first();

        return (is_null($banner_template)) ? false : $banner_template->enabled;
    }

    /**
     * Get the banner templates associated with this template.
     */
    public function banner_templates(): HasMany
    {
        return $this->hasMany(BannerTemplate::class);
    }
}
