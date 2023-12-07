<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PeopleRequest;
use App\Models\People;

class PeopleController extends Controller
{
    public function create(PeopleRequest $request)
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
}
