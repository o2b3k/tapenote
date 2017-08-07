<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Country;
use Illuminate\Http\Request;
use Validator;

class CountriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        return view('countries.index', ['countries' => Country::all()]);
    }


    public function setDefault(Request $request, $id)
    {
        $country = $this->getValidatedCountry($id);
        $defaultCountry = Country::where('default', true)->first();

        if ($defaultCountry) {
            $defaultCountry->default = false;
            $defaultCountry->save();
        }

        $country->default = true;
        $country->save();

        $request->session()->flash('status', [
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Default country changed successfully'
        ]);

        return redirect()->route('countries.index');
    }


    public function addForm()
    {
        return view('countries.add');
    }


    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), $this->getAddValidationRules(false));
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        Country::create($request->all());
        $request->session()->flash('status', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "Country '" . $request->input('name') . "' is created successfully'"
        ]);

        return redirect()->route('countries.index');
    }


    public function editForm(Request $request, $id)
    {
        return view('countries.add', ['country' => $this->getValidatedCountry($id)]);
    }


    public function edit(Request $request, $id)
    {
        $country = $this->getValidatedCountry($id);
        $validator = Validator::make($request->all(), $this->getAddValidationRules(true));
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $country->fill($request->all());
        $country->save();

        $request->session()->flash('status', [
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Country information updated successfully'
        ]);

        return redirect()->route('countries.add');
    }


    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), $this->getDeleteValidationRules());
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $country = $this->getValidatedCountry($request->input('country_id'));
        if ($country->monuments && $country->monuments->count() > 0) {
            $request->session()->flash('status', [
                'type' => 'success',
                'title' => 'Error',
                'message' => 'Can not delete country which has categories.'
            ]);
            return redirect()->route('countries');
        }

        $country->delete();
        $request->session()->flash('status', [
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Country deleted successfully'
        ]);

        return redirect()->route('countries.index');
    }


    private function getValidatedCountry($id)
    {
        if (!$id) {
            abort(404);
        }

        $country = Country::find($id);
        if (!$country) {
            abort(404);
        }

        return $country;
    }


    private function getDeleteValidationRules()
    {
        return [
            'country_id' => 'present|exists:countries,id'
        ];
    }


    private function getAddValidationRules($isEdit)
    {
        $rules = [
            'name' => ['required', 'max:255',],
            'lat' => 'regex:/\-?[0-9]+(\.[0-9]+)?/',
            'long' => 'regex:/\-?[0-9]+(\.[0-9]+)?/',
        ];

        if (!$isEdit) {
            $rules['name'][] = 'unique:countries';
        }

        return $rules;
    }
}