@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Create post') }}</h3>
                                @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                            </div>
                            <div class="col-4 text-right">
                                <!--a href="" class="btn btn-primary btn-round">{{ __('Back to page post list') }}</a-->
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('fb.savePost', ['id' => $id, 'access' =>  $access])}}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">{{ __('Post information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-name">{{ __('Content') }}</label>
                                    <input type="text" name="content" id="input-name" class="form-control" placeholder="{{ __('Content') }}" required autofocus>


                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-lastName">{{ __('Photo/video') }}</label>
                                    <input type="file" name="img" id="input-lastName" class="form-control" placeholder="{{ __('photo') }}" value="" autofocus>

                                </div>
                                <div class="form-group">
                                <label class="form-control-label" for=""> (date and time):</label>
                                <input type="datetime-local" class="form-control" id="" name="ScTime">
                            </div>

                                <div class="text-center">
                                    <button type="submit" name="submit" value="share" class="btn btn-success mt-4">{{ __('Share NOW!') }}</button>
                                    <button type="submit"  name="submit"value="sc" class="btn btn-success mt-4">{{ __('Schedule') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>

</script>
@endsection
