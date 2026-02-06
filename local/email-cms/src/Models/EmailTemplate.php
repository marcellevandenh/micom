<?php

namespace Surge\EmailCms\Models;

use App\Models\Parameters;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailTemplate extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'email_templates';

    protected $guarded = ['id'];

    protected $casts = [
        'placeholders' => 'array', // store placeholders as JSON
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function previewButton()
    {
        return '<a class="btn btn-sm btn-link" href="'.url('admin/email-template/'.$this->id.'/preview').'">Preview</a>';
    }

    public function sendTestEmailButton()
    {
        $url = url("admin/email-template/{$this->id}/send-test");
        return '<a class="btn btn-sm btn-link" href="'.$url.'"><i class="la la-envelope"></i> Send Test</a>';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */ 
    public function Template() : BelongsTo
    {
        return $this->belongsTo(Parameters::class, 'parameter_id', 'id')->where('index','EMAIL');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
