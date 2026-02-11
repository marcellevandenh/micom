{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>


<x-backpack::menu-item title="Courses" icon="la la-ticket-alt" :link="backpack_url('courses')" />

<x-backpack::menu-item title="Users" icon="la la-user" :link="backpack_url('user')" />

<li class="nav-item">
    <a class="nav-link" href="../../../cp">
        <i class="nav-icon la la-pencil-alt d-block d-lg-none d-xl-block"></i> <span>CMS Dashboard</span>    </a>
</li>


