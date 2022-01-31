@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card">
                <div class="card-header"></div>

                <div class="card-body">
                <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                    <th>Page name</th>
                    <th>Category</th>
                    <th></th>

                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($data as $pages)
              <tr>
                <td>{{$pages['name']}}</td>
                <td>{{$pages['category']}}</td>
                <td> <a type="button" role="button" href = "{{route('fb.indexPagePosts', ['id' => $pages['id'], 'access' =>  $pages['access_token'] ] )}}" class="btn btn-info" >{{ __('My page') }}</a></td>

                      </tr>
                @endforeach
                </tbody>
                </table>
              </div>

                </div>
            </div>
</div>
@endsection
