<?php

namespace App\Models;

use App\Traits\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class Menu
 * @package App\Models
 *
 * @property int $menu_id
 * @property int $parent_id
 * @property string $title
 * @property string $url
 * @property string $type
 * @property int $sort
 * @property int $left
 * @property int $right
 * @property string $status
 * @property static|Collection $parent
 * @property static|Collection $subs
 */
class Menu extends Model
{
    use Status;

    /**
     * @inheritdoc
     * @var string
     */
    public $table = 'menus';

    /**
     * @inheritdoc
     * @var string
     */
    public $primaryKey = 'menu_id';

    /**
     * @inheritdoc
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'title',
        'url',
        'type',
        'sort',
        'left',
        'right',
        'status',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\Menu', 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subs()
    {
        return $this->hasMany('App\Models\Menu', 'parent_id');
    }

    /**
     * @return string
     */
    public function getOptions()
    {
        $options = [];

        $options[] = '<a class="ajax-update-record" href="' .route('get_menu', ['id' => $this->menu_id]). '" data-toggle="tooltip" title="Cập nhật menu"><i class="fa fa-pencil text-info"></i></a>';

        $options[] = '<a data-title="menu" class="remove-record" href="' .route('remove_menu', ['id' => $this->menu_id]). '" data-toggle="tooltip" title="Xóa menu"><i class="fa fa-trash text-danger"></i></a>';

        return implode('&nbsp;&nbsp;&nbsp;', $options);
    }

    /**
     * @param bool $hasSubs
     * @return string
     */
    public function getLiClasses($hasSubs = false)
    {
        return $hasSubs ? 'dropdown' : '';
    }

    /**
     * @param bool $hasSubs
     * @return string
     */
    public function dropDownClass($hasSubs = false)
    {
        return $hasSubs ? 'dropdown-toggle' : 'menu-item';
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return ($this->url) ? $this->url : '#';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSubMenu()
    {
        /* @var Collection|Menu[] $subs */
        $subs = $this->subs()
            ->whereStatus('enable')
            ->orderBy('sort', 'asc')->get();

        if ($subs->count()) {

            return view('frontend.layouts.components.sub_menu', [
                'subs' => $subs
            ]);
        }
    }

    public function getSubSideBarMenu()
    {
        /* @var Collection|Menu[] $subs */
        $subs = $this->subs()
            ->whereStatus('enable')
            ->orderBy('sort', 'asc')->get();

        if ($subs->count()) {

            return view('frontend.layouts.components.sub_menu_side_bar', [
                'subs' => $subs
            ]);
        }
    }
}
