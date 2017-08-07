@extends('layouts.dashboard')

@section('title', 'Edit user information')

@section('content')
    <article class="content">
        <section class="section">
            <div class="card">
                <div class="card-block">
                    <div class="title-block">
                        <h3 class="title">
                            Edit user information
                        </h3>
                    </div>
                    <form action="{{ route('users.edit', ['id' => $user->id]) }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name">First Name</label>
                                    <input type="text" id="name" name="name" class="form-control boxed"
                                           value="{{ old('name', $user->name) }}">
                                    @if ($errors->has('name'))
                                        <span class="has-error">
                                            {{ $errors->first('name') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
                                    <label for="surname">Last Name</label>
                                    <input type="text" id="surname" name="surname" class="form-control boxed"
                                           value="{{ old('surname', $user->surname) }}">
                                    @if ($errors->has('surname'))
                                        <span class="has-error">
                                            {{ $errors->first('surname') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">E-Mail</label>
                            <input type="text" id="email" class="form-control boxed" value="{{ $user->email }}"
                                   readonly>
                        </div>
                        <div class="form-group">
                            <?php $authUser = Auth::user(); ?>
                            @if ($authUser != null && $authUser->id != $user->id)
                            <button type="button" data-id="{{ $user->id }}" class="btn btn-danger btn-delete">
                                <fa class="fa fa-trash"></fa>
                                Delete
                            </button>
                            @endif
                            <div class="pull-right">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-angle-left"></i>
                                    Back
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </article>
    <form id="delete-user-form" action="{{ route('users.delete') }}" method="POST" class="hidden">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" id="user_id" name="user_id">
    </form>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('js/snippets/delete-user.js') }}"></script>
@endpush