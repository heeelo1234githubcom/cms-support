<?php

namespace App\Models;

use App\Traits\Status;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Contact
 * @package App\Models
 *
 * @property int $contact_id
 * @property string $contact_name
 * @property string $contact_email
 * @property string $contact_phone
 * @property string $contact_content
 * @property string $status
 */
class Contact extends Model
{
    use Status;

    /**
     * @inheritdoc
     * @var string
     */
    public $table = 'contacts';

    /**
     * @inheritdoc
     * @var string
     */
    public $primaryKey = 'contact_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'contact_name',
        'contact_email',
        'contact_phone',
        'contact_content',
        'status',
    ];

    /**
     * @return string
     */
    public function getViewStatusText()
    {
        return ($this->isDisable()) ? 'Chưa xem' : 'Đã xem';
    }

    /**
     * @return string
     */
    public function getOptions()
    {
        return '<a class="view-link" href="' .route('get_contact', ['id' => $this->contact_id]). '" data-toggle="tooltip" title="' .trans('common.view_contact'). '"><i class="fa fa-eye text-info"></i></a>';
    }
}
