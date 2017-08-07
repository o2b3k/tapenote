@extends('layouts.dashboard')

@section('title')
    @if (isset($country))
        Edit country information
    @else
        Add new country
    @endif
@endsection

@section('content')
    <article class="content">
        <section class="section">
            <div class="card">
                <div class="card-block">
                    <div class="card-title-block">
                        <h3 class="title">
                            @if (isset($country))
                                Edit country information
                            @else
                                Add new country
                            @endif
                        </h3>
                    </div>
                    <section class="example">
                        <form action="{{ isset($country) ? route('countries.edit', ['id' => $country->id]) : route('countries.add') }}" method="POST">
                            {{ csrf_field() }}
                            @isset($country)
                                <input type="hidden" name="_method" value="PUT">
                            @endisset
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name">Country name</label>
                                <input type="text" class="form-control boxed" name="name"
                                       placeholder="Country name"
                                       value="{{ old('name', isset($country) ? $country->name : '') }}">
                                @if ($errors->has('name'))
                                    <span class="has-error">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group{{ $errors->has('lat') ? ' has-error' : '' }}">
                                        <label for="lat">Latitude</label>
                                        <input type="text" class="form-control boxed" name="lat"
                                               placeholder="Map marker's latitude"
                                               value="{{ old('lat', isset($country) ? $country->lat : '') }}">
                                        @if ($errors->has('lat'))
                                            <span class="has-error">{{ $errors->first('lat') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group{{ $errors->has('long') ? ' has-error' : '' }}">
                                        <label for="long">Longitude</label>
                                        <input type="text" class="form-control boxed" name="long"
                                               placeholder="Map marker's longitude"
                                               value="{{ old('long', isset($country) ? $country->lat : '') }}">
                                        @if ($errors->has('long'))
                                            <span class="has-error">{{ $errors->first('long') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary pull-right" name="submit">
                                <i class="fa fa-save"></i> Save
                            </button>
                        </form>
                    </section>
                </div>
            </div>
        </section>
    </article>
@endsection