@extends('statamic::layout')
@section('title', 'Import Redirects')

@section('content')
    <form action="{{ cp_route('redirect-urls.import.submit') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

        <header class="mb-3">
            <div class="breadcrumb flex">
                <a href="{{ cp_route('redirect-urls.dashboard') }}" class="flex-initial flex p-1 -m-1 items-center text-xs text-grey-70 hover:text-grey-90">
                    <svg viewBox="0 0 24 24" class="align-middle h-6 w-4 rotate-180">
                        <path fill="currentColor" fill-rule="evenodd" d="m10.414 7.05 4.95 4.95-4.95 4.95L9 15.534 12.536 12 9 8.464z"></path>
                    </svg> 
                    <span>Redirect Urls</span>
                </a>
            </div> 
            
            <div class="flex items-center">
                <h1 class="flex-1">Import Redirects</h1>
            </div>
        </header>

        <div class="card content p-2 my-3 overflow-x-scroll">
            <p class="my-2">
                Import redirects from a CSV/XCLS file with <code>From</code>, <code>To</code> and <code>Type</code> headers.
            </p>

            <div>
                <label for="file" style="font-weight:600;">CSV</label>
                <input id="file" name="file" type="file" tabindex="1" class="input-text mb-2 mt-1 cursor-pointer" accept="text/csv">
            </div>

            <div>
                <button class="btn-primary">Import</button>
            </div>
        </div>
    </form>
@endsection
