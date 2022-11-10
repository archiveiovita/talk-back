@extends('front.app')
@section('content')

    <main class="notFoundContent">
    <div class="container">
      <div class="row justify-content-center align-items-center flex-column">
        <div class="title">
          404
        </div>
        <div>
          <p>Ups. ceva nu a mers bine.</p>
          <p>Incercati inca o data sau alegeti o optiune din meniul de sus.</p>
          <p>You can continue shopping by pressing one of the following options:</p>
        </div>
        <div class="buttons">
          <a href="{{ url('/') }}">Home</a>
        </div>
      </div>
    </div>
    </main>

@stop
