<?php


namespace app\common\model;

use think\model\concern\SoftDelete;

class QuestionSuggest extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $dateFormat = 'Y-m-d';

    protected $type = [
        'start_time'  =>  'timestamp',
        'end_time'  =>  'timestamp'
    ];

    protected $hidden = [
        'create_time',
        'update_time',
        'delete_time'
    ];
}
