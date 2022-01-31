@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card">
                <div class="card-header">{{ __('') }}</div>

                <div class="card-body">
                @if (!session('fb_user_access_token'))
                <a type="button" role="button" href = "{{route('fb.login')}}" class="btn btn-success" >{{ __('Connect') }}</a>
            @else <p> you are logged in

            <a type="button" role="button" href = "{{route('fb.indexPages')}}" class="btn btn-info" >{{ __('My pages') }}</a>
            @endif

                </div>
            </div>
</div>
@endsection
