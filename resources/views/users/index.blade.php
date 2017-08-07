@extends('layouts.dashboard')

@section('title', 'Users')

@section('content')
    <article class="content">
        <section class="section">
            <div class="card">
                <div class="card-block">
                    <div class="card-title-block">
                        <h3 class="title">
                            Users list
                            <a href="{{ route('users.add') }}" class="btn btn-primary pull-right">
                                <i class="fa fa-plus"></i> Add user
                            </a>
                        </h3>
                    </div>
                    <section class="example">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>First name</th>
                                <th>Last name</th>
                                <th>E-Mail name</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php  $authUser = Auth::user() ?>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->surname }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('users.editForm', ['id' => $user->id]) }}"
                                               class="btn btn-secondary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @if ( $user->id != $authUser->id )
                                                <button data-id="{{ $user->id }}" class="btn btn-danger btn-delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="text-success">
                                            <h1 class="text-center"><i class="fa fa-info-circle"></i></h1>
                                            <h3 class="text-center">There are not users yet</h3>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </section>
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