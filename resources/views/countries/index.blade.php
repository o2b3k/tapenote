@extends('layouts.dashboard')

@section('title', 'Countries list')

@section('content')
    <article class="content">
        <section class="section">
            <div class="card">
                <div class="card-block">
                    <div class="card-title-block">
                        <h3 class="title">
                            Countries list
                            <a href="{{ route('countries.addForm') }}" class="btn btn-primary pull-right">
                                <i class="fa fa-plus"></i> Add country
                            </a>
                        </h3>
                    </div>
                    <section class="example">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($countries as $country)
                                <tr>
                                    <td>{{ $country->id }}</td>
                                    <td>
                                        <a href="{{ route('categories.index', ['countryId' => $country->id]) }}">{{ $country->name }}</a>
                                        @if ($country->default)
                                            <button class="btn btn-primary btn-sm rounded-s">Default</button>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if (!$country->default)
                                                <a href="{{ route('countries.setDefault', ['id' => $country->id]) }}"
                                                   class="btn btn-success">
                                                    <i class="fa fa-check-circle"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('countries.editForm', ['id' => $country->id]) }}"
                                               class="btn btn-secondary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" data-id="{{ $country->id }}"
                                                    class="btn btn-danger btn-delete">
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
                                            <h3 class="text-center">There is not any country yet</h3>
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
    <form id="delete-country-form" action="{{ route('countries.delete') }}" method="POST" class="hidden">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" id="country_id" name="country_id">
    </form>
@endsection


@push('scripts')
<script type="text/javascript" src="{{ asset('js/snippets/delete-country.js') }}"></script>
@endpush