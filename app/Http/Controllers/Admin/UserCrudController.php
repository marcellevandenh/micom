<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Hash;

class UserCrudController extends CrudController
{
    use CreateOperation { store as traitStore; }
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation { update as traitUpdate; }

    public function setup()
    {
        CRUD::setModel(User::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/user');
        CRUD::setEntityNameStrings('user', 'users');

    }
    public function setupListOperation()
    {

        CRUD::column([
            'label' => 'User Name',
            'name' => 'name',
            'type' => 'closure',
            'function' => function ($entry) {
                $url = url($this->crud->route.'/'.$entry->getKey().'/show');

                return '<a href="'.$url.'">'.$entry->name.'</a>';
            },
            'escaped' => false,
        ]);

        CRUD::column('email')->label('Email');

        CRUD::column('super')->label('Super Admin')->type('checkbox');
    }

    public function setupCreateOperation()
    {

        CRUD::setValidation(UserStoreRequest::class);

        CRUD::addField([
            'name' => 'name',
            'label' => 'Username',
        ]);

        CRUD::addField([
            'name' => 'email',
            'type' => 'email',
        ]);

        CRUD::field('password')->type('password');

        CRUD::field('password_confirmation')->type('password');
        if (backpack_user()->id == 1) {
            // if creating user and logged in as user ID 1 then show super checkbox
            CRUD::field('super')->type('checkbox')->label('Super User');
        }

        CRUD::addField([
            'label' => 'Roles',
            'type' => 'checklist',
            'name' => 'roles',
            'entity' => 'roles',
            'attribute' => 'name',
            'model' => config('permission.models.role'),
            'pivot' => true,
        ]);
    }

    public function setupUpdateOperation()
    {

        CRUD::setValidation(UserRequest::class);

        $this->crud->query->with(['roles', 'permissions']);

        CRUD::addField([
            'name' => 'name',
            'label' => 'Username',
        ]);

        CRUD::addField([
            'name' => 'email',
            'type' => 'email',
        ]);

        if (backpack_user()->id != $this->crud->getCurrentEntryId()) {
            // if not editing own user then show password field
            // CRUD::field('password')->type('password')->label('Password (only fill in to change)')->tab('General');
            CRUD::addField([
                'name' => 'password_information',
                'label' => 'Password',
                'type' => 'text',
                'attributes' => [
                    'readonly' => 'readonly',
                    'style' => 'background-color: #e9ecef; cursor:not-allowed',
                ],
                'default' => 'You cannot change your own password here. Please use the "My Account" link in the top right menu to change your password.',
                'hint' => 'To change your password, please use the "My Account" link in the top right menu.',
            ]);
        } else {
            // if editing own user then show readonly password field with info
            CRUD::addField([
                'name' => 'password_information',
                'label' => 'Password',
                'type' => 'text',
                'attributes' => [
                    'readonly' => 'readonly',
                    'style' => 'background-color: #e9ecef; cursor:not-allowed',
                ],
                'default' => 'You cannot change your own password here. Please use the "My Account" link in the top right menu to change your password.',
                'hint' => 'To change your password, please use the "My Account" link in the top right menu.',
            ]);
        }

        if (backpack_user()->id == 1) {
            // if creating user and logged in as user ID 1 then show super checkbox
            CRUD::field('super')->type('checkbox')->label('Super User');
        }

        CRUD::addField([
            'label' => 'Roles',
            'type' => 'checklist',
            'name' => 'roles',
            'entity' => 'roles',
            'attribute' => 'name',
            'model' => config('permission.models.role'),
            'pivot' => true,
        ]);
    }


    public function update()
    {
        $this->crud->setRequest($this->crud->validateRequest());
        $this->crud->setRequest($this->handlePasswordInput($this->crud->getRequest()));
        $this->crud->unsetValidation();

        return $this->traitUpdate();
    }

    public function store()
    {
        $this->crud->setRequest($this->crud->validateRequest());
        $this->crud->setRequest($this->handlePasswordInput($this->crud->getRequest()));
        $this->crud->unsetValidation();

        return $this->traitStore();
    }

    protected function handlePasswordInput($request)
    {
        $request->request->remove('password_confirmation');

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        return $request;
    }

        protected function setupShowOperation()
    {
        $this->crud->setHeading('User: '.$this->crud->getCurrentEntry()->name);

        CRUD::column('name')->label('Name');
        CRUD::column('email')->label('Email');
        CRUD::column('super')->label('Super Admin')->type('checkbox');


        CRUD::addColumn([
            'label' => 'Roles',
            'type' => 'closure',
            'function' => function ($entry) {
                return $entry->roles->map(function ($role) {
                    return '<span class="badge bg-secondary">'.$role->name.'</span>';
                })->implode(' ');
            },
            'escaped' => false,
        ]);
    }
}
