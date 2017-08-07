@extends('layouts.dashboard')

@section('title', 'Add New User')

@section('content')
    <article class="content">
        <section class="section">
            <div class="card">
                <div class="card-block">
                    <div class="card-title-block">
                        <h3 class="title">Add new user</h3>
                    </div>
                    <section class="example">
                        <form action="{{ route('users.invite') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input type="text" class="form-control boxed" name="email"
                                       placeholder="New user's email address">
                                @if ($errors->has('email'))
                                    <span class="has-error">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary pull-right" name="submit">
                                <i class="fa fa-send"></i> Send invitation
                            </button>
                        </form>
                    </section>
                </div>
            </div>
        </section>
    </article>
@endsection