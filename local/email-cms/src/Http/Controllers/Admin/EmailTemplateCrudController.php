<?php

namespace Surge\EmailCms\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Parameters;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Blade;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Surge\EmailCms\Http\Requests\EmailTemplateRequest;
use Surge\EmailCms\Models\EmailTemplate;

/**
 * Class EmailTemplateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EmailTemplateCrudController extends CrudController
{
    use CreateOperation;
    use UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(EmailTemplate::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/email-template');
        CRUD::setEntityNameStrings('email template', 'email templates');

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addClause('orderBy', 'id', 'asc');
        // CRUD::setFromDb(); // set columns from db columns.
        CRUD::column('id');
        CRUD::column('name');
        CRUD::column('subject');

        CRUD::column([
            'name' => 'Template',
            'type' => 'relationship',
            'label' => 'Template',
            'attribute' => 'name',
            'model' => 'App/Http/Models/Parameters'
        ]);

    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {

        $this->crud->setValidation(EmailTemplateRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.
        CRUD::field('name');
        CRUD::field('subject');

        CRUD::field('cc')
            ->label('Cc Email address(es)')
            ->type('text')
            ->attributes(['placeholder' => 'Enter email address to be cc seperated by ;']);
        CRUD::field('bcc')
            ->label('Bcc Email address(es)')
            ->type('text')
            ->attributes(['placeholder' => 'Enter email address to be bcc seperated by ;']);
             // Rich editor for email body
        $this->crud->addField([
            'name' => 'body',
            'type' => 'ckeditor', // or 'summernote', 'tinymce'
            'label' => 'Email Body (use {{ $placeholder }})',
        ]);

        // JSON editor for placeholders
        $this->crud->addField([
            'name' => 'placeholders',
            'label' => 'Placeholders (JSON)',
            'type' => 'table',
            'entity_singular' => 'placeholder',
            'columns' => [
                'key' => 'Key (e.g. name)',
                'example' => 'Example Value (e.g. John)',
            ],
        ]);

        $this->crud->addField([
            'name' => 'attachment',
            'type' => 'upload',
            'label' => 'Attachment',
            'upload' => true,
            'withFiles' => [
                'disk' => 'public',
                'path' => 'uploads',
            ]
        ]);

        CRUD::field('logo')->label('Add logo');
        CRUD::field('button_text')->type('text')->label('Button Text');
        CRUD::field('button_url')->type('text')->label('Button URL eg.https://example.com/user/{{user_id}}/dashboard');

        CRUD::addField([
            'name' => 'Template',
            'type' => 'relationship',
            'label' => 'Use as Template for',
            'model' => 'App\Models\Parameters',
              'options'   => function ($query) {
                return $query->where('index', 'EMAIL')->get();
            },
        ]);
        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        // CRUD::setValidation(EmailTemplateRequest::class);
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $entry = $this->crud->getCurrentEntry()->load('Template');

        $this->crud->addButtonFromModelFunction('line', 'send_test', 'sendTestEmailButton', 'end');


        CRUD::column('name');
        CRUD::column('subject');
        CRUD::column('cc');
        CRUD::column('bcc');
        CRUD::column([
        'name' => 'body',
        'label' => 'Body Text',
         'type' => 'easymde',
        ]);

        $this->crud->column([
            'name'  => 'placeholders',
            'label' => 'Placeholders',
            'type'  => 'custom_html',
            'value' => function ($entry) {
                if (empty($entry->placeholders)) {
                    return '<em>No placeholders set</em>';
                }

                $placeholders = is_array($entry->placeholders)
                    ? $entry->placeholders
                    : json_decode($entry->placeholders, true);

                if (!$placeholders || !is_array($placeholders)) {
                    return '<em>Invalid JSON</em>';
                }

                $html = '<ul style="list-style-type: disc; padding-left: 20px;">';
                foreach ($placeholders as $placeholder) {
                    $key = $placeholder['key'] ?? '(no key)';
                    $example = $placeholder['example'] ?? '';
                    $html .= "<li style='list-style-type: none;'>{{ $key }} : {$example}</li>";
                }
                $html .= '</ul>';

                return $html;
            },
        ]);

        CRUD::column('attachment');
        CRUD::column('logo')->label('Add Logo')->type('checkbox');
        // CRUD::column('parameter_id')->label('Use as Template for Action');
        CRUD::column('button_text');
        CRUD::column('button_url');
        CRUD::column([
            'name' => 'Template',
            'type' => 'relationship',
            'label' => 'Template',
            'attribute' => 'name',
            'model' => 'App/Http/Models/Parameters'
        ]);
    }

    public function store()
    {
        // $response = parent::storeCrud(); // Save the entry
        $response = $this->traitStore();
        $entry = $this->crud->entry; // The saved model instance


        // $template = Parameters::find($entry->parameter_id);
        // $template->value = $entry->id;
        // $template->save();


        return $response;
    }

    public function update()  //Overwrite Update to update Parameters file
    {
        $response = $this->traitUpdate();
        $entry = $this->crud->entry; // The saved model instance

        if ($entry->parameter_id ) {
            $template = Parameters::find($entry->parameter_id);
            $template->value = $entry->id;
            $template->save();
        }

        return $response;
    }

}
