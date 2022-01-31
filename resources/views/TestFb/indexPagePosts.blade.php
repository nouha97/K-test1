@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card">
                <div class="card-header">
                    <a type="button" role="button" href = "{{route('fb.createPost' , ['id' => $id, 'access' =>  $access] )}}" class="btn btn-info text-white" >{{ __('Create post') }}</a>
                @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
            </div>

                <div class="card-body">
                <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                    <th style="width:40%">Caption</th>
                    <th>Creation date</th>
                    <th>Attachement type</th>
                    <th></th>


                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($data['postsP'] as $postsP)
              <tr>
              @if(array_key_exists('message', $postsP))
                <td>{{$postsP['message']}}</td>
                @else    <td>Empty caption here</td>
                @endif
                <td>{{$postsP['created_time']->format('d-m-Y')}}</td>
                @if(array_key_exists('attachments', $postsP))
                <td>{{$postsP['attachments'][0]['type']}}</td>
                @else
                <td>Statut</td>
                @endif
                <td>Posted</td>
                <td>
                     <a type="button" role="button" href = "{{route('fb.delete' , ['id' => $postsP['id'], 'access' =>  $access , 'idPage' => $id] )}}" class="btn btn-info" >{{ __('Remove') }}</a>
            </td>
                      </tr>
                @endforeach

                @foreach ($data['postsS'] as $postsS)
              <tr>
              @if(array_key_exists('message', $postsS))
                <td>{{$postsS['message']}}</td>
                @else    <td>Empty caption here</td>
                @endif
                <td>{{$postsS['created_time']->format('d-m-Y')}}</td>
                @if(array_key_exists('attachments', $postsS))
                <td>{{$postsS['attachments'][0]['type']}}</td>
                @else
                <td>Statut</td>
                @endif
                <td>scheduled</td>
                      </tr>
                @endforeach
                </tbody>
                </table>
              </div>

                </div>
            </div>
<!--Creation post modal !-->

  </div>
</div>
</div>
@endsection
