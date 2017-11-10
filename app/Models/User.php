<?php

namespace App\Models;

use App\Traits\Status;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

/**
 * Class User
 * @package App\Models
 *
 * @property int $user_id
 * @property string $name
 * @property string $password
 * @property string $email
 * @property string $level
 * @property string $status
 * @property Collection $info
 */
class User extends Authenticatable
{
    use Notifiable, Status;

    const IS_ADMIN_USER = 'admin';

    const IS_NORMAL_USER = 'normal';

    /**
     * @inheritdoc
     * @var string
     */
    public $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function info()
    {
        return $this->hasMany('App\Models\UserMeta', 'user_id');
    }

    /**
     * @param array $data
     */
    public function addInfo($data = [])
    {
        if ($data) {

            foreach ($data as $key => $value) {

                /**
                 * update user info
                 */
                try {

                    $this->info()->create([
                        'user_id' => $this->user_id,
                        'meta_key' => $key,
                        'meta_value' => $value
                    ]);

                } catch (\Exception $e) {}
            }
        }
    }

    /**
     * @param array $data
     */
    public function updateInfo($data = [])
    {
        if ($data) {
            foreach ($data as $key => $value) {

                /**
                 * update user info
                 */
                $this->info()->updateOrCreate([
                    'user_id' => $this->user_id,
                    'meta_key' => $key
                ], [
                    'meta_value' => $value
                ]);
            }
        }
    }

    /**
     * @return bool
     */
    public function isNormalUser()
    {
        return (self::IS_NORMAL_USER === $this->level);
    }

    /**
     * @return array
     */
    public function getUserInfo()
    {
        return $this->info->pluck('meta_value', 'meta_key')->toArray();
    }

    /**
     * @return array
     */
    public function withInfo()
    {
        $user = $this->toArray();
        $user = array_merge($user, $this->getUserInfo());

        return $user;
    }

    /**
     * @return string
     */
    public function getOptions()
    {
        $options = [];

        if ($this->user_id != 1) {
            $options[] = '<a class="ajax-update-record" href="' .route('get_user', ['id' => $this->user_id]). '" data-toggle="tooltip" title="Cập nhật người dùng"><i class="fa fa-pencil text-info"></i></a>';

            if ($this->isDisable()) {
                $options[] = '<a data-title="người dùng" class="enable-record" href="' .route('change_user_status', ['id' => $this->user_id]). '" data-toggle="tooltip" title="Kích hoạt người dùng"><i class="fa fa-eye text-primary"></i></a>';
            } else {
                $options[] = '<a data-title="người dùng" class="remove-record" href="' .route('change_user_status', ['id' => $this->user_id]). '" data-toggle="tooltip" title="Xóa người dùng"><i class="fa fa-trash text-danger"></i></a>';
            }
        }

        return implode('&nbsp;&nbsp;&nbsp;', $options);
    }

    /**
     * @return string
     */
    public function getLevel()
    {
        return ($this->level == 'admin') ? 'Quản trị' : 'Người dùng thường';
    }
}
