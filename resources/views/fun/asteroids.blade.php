@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Asteroids</div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <iframe class="col-md-10" src="https://www.silvergames.com/en/asteroids/iframe" width="100%" height="470" style="margin:0;padding:0;border:0"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
