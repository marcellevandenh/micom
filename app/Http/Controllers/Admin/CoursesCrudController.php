<?php

namespace App\Http\Controllers\Admin;

use App\Models\TicketTypes;
use App\Http\Requests\CoursesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CoursesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CoursesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Courses::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/courses');
        CRUD::setEntityNameStrings('courses', 'courses');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // CRUD::setFromDb(); // set columns from db columns.

        CRUD::enableExportButtons();

        // $this->crud->addButtonFromView('line', 'view_event', 'show_event_link', 'beginning');
        // $this->crud->allowAccess('update');

        // Automatically add all columns from the database
        // CRUD::setFromDb();

        CRUD::filter('published')
        ->type('dropdown')
        ->values([
            1 => 'True',
            0 => 'False',
        ])
        ->whenActive(function($value) {
            CRUD::addClause('where', 'published', $value);
        });

        // Remove the default "name" column
        CRUD::removeColumn('name');

        // Add your custom clickable name column
        CRUD::addColumn([
            'label' => 'Event Name',
            'name' => 'name',
            'type' => 'closure',
            'function' => function($entry) {
                $url = url($this->crud->route.'/'.$entry->getKey().'/show');
                return '<a href="' . $url . '">' . e($entry->name) . '</a>';
            },
            'escaped' => false, // allow HTML link
        ]);

        $this->crud->query->withCount('attendees');
        CRUD::addColumn([
            'name'  => 'attendees_count',
            'label' => 'Tickets Sold',
            'type'  => 'number',
        ]);

        $this->crud->addColumn([
            'name'      => 'venue',
            'type'      => 'relationship',
            'label'     => 'Venue',
            'attribute' => 'name',
        ]);

        $this->crud->addColumn([
            'name'      => 'category',
            'type'      => 'relationship',
            'label'     => 'Event Category',
            'attribute' => 'name',
        ]);

        CRUD::column('published')->type('checkbox');


        CRUD::column('slug');
        CRUD::column('price')->type('number')->prefix('£')->decimals(2);

    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CoursesRequest::class);

        // CRUD::setFromDb(); // set fields from db columns.
        $currentInstance = $this->crud->getCurrentEntry();
        if(!$currentInstance) {
            $currentID = 0; //dummy value
        }else{
            $currentID = $currentInstance->id;
            // $defaultDeposit = $currentInstance->minimum_deposit_currency;
            // $defaultDepositPerc = $currentInstance->minimum_deposit_percent;
            // $defaultMaxDays = $currentInstance->maximum_days_for_staged_payments;
        }

        CRUD::field('name')->label('Event Name')->tab('Listing');

        CRUD::field('slug')->type('slug')
        ->label('Slug')
        ->target('name')->tab('Listing')
        ->attributes(['readonly' => 'readonly']);

        CRUD::field([
            'label'     => 'Event Category',
            'type'      => 'select',
            'name'      => 'event_category_id',
            'entity'    => 'category',
            'attribute' => 'name', // or title or whatever you use
            'model'     => \App\Models\Category::class,
            'tab'    => 'Listing',
        ]);

        CRUD::field([
            'label'     => 'Venue',
            'type'      => 'select',
            'name'      => 'venue_id',
            'entity'    => 'venue',
            'attribute' => 'name', // or title or whatever you use
            'model'     => \App\Models\Venues::class,
            'tab'    => 'Listing',
        ]);

        CRUD::field('start_date')->label('Start Date')->type('date')->tab('Listing');
        CRUD::field('start_time')->label('Start Time')->type('time')->tab('Listing');
        CRUD::field('end_date')->label('End Date')->type('date')->tab('Listing');
        CRUD::field('end_time')->label('End Time')->type('time')->tab('Listing');
        CRUD::field('price')->label('Price')->tab('Listing')->required(true)->type('number')->prefix('£')->attributes(["step" => "0.01"]);
        CRUD::field('description')->type('ckeditor')->escape('false')->label('Description')->tab('Listing');
        CRUD::field('slug')->type('slug')
        ->label('Slug')
        ->target('name')->tab('Listing')
        ->attributes(['readonly' => 'readonly']);

        CRUD::addField([
            'name'      => 'sponsors', // the relationship name
            'label'     => 'Sponsors',
            'type'      => 'select2_multiple',
            'tab'       => 'Listing',
            'entity'    => 'sponsors', // function name in the model
            'model'     => "App\\Models\\Companies",
            'attribute' => 'name', // what to show in the select box
            'pivot'     => true, // important for many-to-many
        ]);

        CRUD::field('hero_image')->type('upload')->upload('true')->withFiles([
            'disk' => 'public',
            'path' => 'uploads',
        ])->label('Image')->tab('Media')->hint('Right hand side image on Event details page and Main Image on Event listing page');
        CRUD::field('detail_image_1')->type('upload')->upload('true')->withFiles([
            'disk' => 'public',
            'path' => 'uploads',
            ])->label('Detail Image 1')->tab('Media')->hint('Full width image Event Details section');

        CRUD::field('detail_heading_1')->label('Heading 1')->tab('Detail');
        // CRUD::field('detail_text_1')->label('Text 1')->tab('Detail');
        CRUD::field('detail_text_1')->type('ckeditor')->escape('false')->options([
        'enterMode'      => 'CKEDITOR.ENTER_BR',     // Use <br> for Enter
        'shiftEnterMode' => 'CKEDITOR.ENTER_P',      // Shift+Enter creates a <p>
         ])->tab('Detail');

        CRUD::field('published')->label('Publish')->tab('Other');
        CRUD::field('is_bookable')->label('Bookable')->tab('Other');
        // CRUD::field('use_time')->label('Use Time')->tab('Other');

        //  CRUD::field([   // relationship
        //     'name' => 'tickets', // the method on your model that defines the relationship
        //     'type' => "relationship",
        //     'tab' =>'Tickets',
        //     'subfields' => [
        //         [
        //             'name' => 'ticket_type_id',
        //             'label' => 'Ticket Type',
        //             'type' => 'select2_from_ajax',
        //             'entity' => 'ticketType',
        //             'model' => TicketTypes::class,
        //             'attribute' => 'name',
        //             'ajax' => true,
        //             'data_source' => '/admin/event-template/available-ticket-types/'. $currentID,
        //             'wrapper' => [
        //                 'class' => 'form-group col-md-4',
        //             ],
        //         ],
        //         [
        //             'name' => 'price',
        //             'type' => 'number',
        //             'prefix' => '£',
        //             'suffix' => ' + VAT',
        //             'attributes' => ['step' => '0.01', 'min' => '0'],
        //             'wrapper' => [
        //                 'class' => 'form-group col-md-3',
        //             ],
        //         ],
        //         // [
        //         //     'name'  => 'xero_account_code',
        //         //     'label' => 'Xero Code',
        //         //     'type'  => 'text',
        //         //     'value' => CRUD::getCurrentOperation() === 'create'
        //         //         ? (string) xeroParam('EVENTS_ACCOUNT')
        //         //         : null,
        //         //     'wrapper' => [
        //         //         'class' => 'form-group col-md-1',
        //         //     ],
        //         // ],
        //         [
        //             'name' => 'fee',
        //             'type' => 'number',
        //             'prefix' => '£',
        //             'value' => 0,
        //             'default' => 0,
        //             'attributes' => ['step' => '0.01', 'min' => '0'],
        //             'wrapper' => [
        //                 'class' => 'form-group col-md-2 d-none',
        //             ],
        //         ],
        //         [
        //             'name' => 'qty',
        //             'type' => 'number',
        //             'attributes' => ['step' => '1', 'min' => '0'],
        //             'wrapper' => [
        //                 'class' => 'form-group col-md-2',
        //             ],
        //         ],
        //         [
        //             'name' => 'remaining_tickets',
        //             'type' => 'number',
        //             'label' => 'Remaining',
        //             'wrapper' => [
        //                 'class' => 'form-group col-md-1',
        //             ],
        //             'attributes' => ['readonly' => 'readonly', 'disabled' => 'disabled'],
        //         ],
        //     ],
        //     'label' => "Tickets",
        //     'attribute' => 'tickets',
        //     'new_item_label'  => 'Add New Ticket Type',
        // ]);

        // CRUD::addField([
        //     'name' => 'instruction',
        //     'type' => 'custom_html',
        //     'tab' =>'Tickets',
        //     'value' => '
        //         <div class="alert alert-info">
        //             <p><strong>Xero Account Code</strong></p>
        //             <p>This defaults to the EVENTS_ACCOUNT Paramater Setup Xero codes</p>
        //             <p>If updated make sure this is a Revenue account and accept payments = true</p>
        //         </div>
        //     ',
        //     'wrapper' => [
        //         'class' => 'col-12',
        //     ],
        // ])->afterField('tickets');

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
        $this->setupCreateOperation();
    }
}
