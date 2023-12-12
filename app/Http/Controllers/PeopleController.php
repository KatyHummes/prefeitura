<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PeopleRequest;
use App\Models\People;
use Inertia\Inertia;

class PeopleController extends Controller
{

    public function index()
    {
        $peoples = People::all();
        return Inertia::render('People/Peoples', [
            'peoples' => $peoples
        ]);
    }

    public function store(PeopleRequest $request)
    {
        $sex = $request->input('sex')['name'];
        $birthDate = \Carbon\Carbon::parse($request->input('birth'))->format('Y-m-d');

        People::create([
            'name' => $request->input('name'),
            'birth' => $birthDate,
            'cpf' => $request->input('cpf'),
            'sex' => $sex,
            'city' => $request->input('city'),
            'neighborhood' => $request->input('neighborhood'),
            'street' => $request->input('street'),
            'number' => $request->input('number'),
            'complement' => $request->input('complement'),
        ]);
    }

    public function edit($id)
    {
        $people = People::findOrFail($id);

        return Inertia::render('People/Edit', [
            'people' =>  $people
        ]);
    }


    public function update(PeopleRequest $request, $id)
    {
        $people = People::findOrFail($id);
        $birthDate = \Carbon\Carbon::parse($request->input('birth'))->format('Y-m-d');

        $people->update([
            'name' => $request->input('name'),
            'birth' => $birthDate,
            'cpf' => $request->input('cpf'),
            'sex' => $request->input('sex'),
            'city' => $request->input('city'),
            'neighborhood' => $request->input('neighborhood'),
            'street' => $request->input('street'),
            'number' => $request->input('number'),
            'complement' => $request->input('complement'),
        ]);
    }
}
