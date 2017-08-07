@extends('layouts.dashboard')

@section('title', 'Monuments list')

@section('content')
    <article class="content">
        <section class="section">
            <div class="card">
                <div class="card-block">
                    <div class="card-title-block">
                        <h3 class="title">Monuments</h3>
                    </div>
                    <section class="section">
                        <table class="table">
                            <thead>
                            <tr>
                                <td>#</td>
                                <td>Name</td>
                                <td>Category</td>
                                <td>Actions</td>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($monuments as $monument)
                                <tr>
                                    <td>{{ $monument->id }}</td>
                                    <td>
                                        <a href="{{ route('monuments.view', ['id' => $monument->id]) }}">{{ $monument->name }}</a>
                                    </td>
                                    <td>
                                        <a href="{{ route('categories.view', ['id' => $monument->category->id]) }}">
                                            {{ $monument->category->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('monuments.editForm', ['id' => $monument->id]) }}"
                                               class="btn btn-secondary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button data-id="{{ $monument->id }}"
                                                    class="btn btn-danger btn-delete-monument">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="text-success">
                                            <h1 class="text-center"><i class="fa fa-info-circle"></i></h1>
                                            <h3 class="text-center">There is not any monument yet</h3>
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
    <form id="delete-monument-form" class="hidden" action="{{ route('monuments.delete') }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" id="monument_id" name="monument_id">
    </form>
@endsection

@push('scripts')
<script type="application/javascript" src="{{ asset('js/snippets/delete-monument.js') }}"></script>
@endpush