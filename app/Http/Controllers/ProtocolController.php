<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Protocol;
use App\Http\Requests\ProtocolRequest;
use App\Models\People;
use Inertia\Inertia;

class ProtocolController extends Controller
{
    public function index()
    {
        $peoples = People::get(['id', 'name']);
        $protocols = Protocol::with('people')->get();
        return Inertia::render('Protocols', [
            'peoples' => $peoples,
            'protocols' => $protocols,
        ]);
    }

    public function store(ProtocolRequest $request)
    {
        $datedate = \Carbon\Carbon::parse($request->input('date'))->format('Y-m-d');
        $termDate = \Carbon\Carbon::parse($request->input('term'))->format('Y-m-d');
        // dd($termDate, $request->all());
        Protocol::create([
            'date' => $datedate,
            'description' => $request->input('description'),
            'term' =>  $termDate,
            'people_id' => $request->input('people_id')['id']
        ]);
    }
    public function update(ProtocolRequest $request, $id)
    {
        $protocol = Protocol::findOrFail($id);
        $datedate = \Carbon\Carbon::parse($request->input('date'))->format('Y-m-d');
        $termDate = \Carbon\Carbon::parse($request->input('term'))->format('Y-m-d');
        // dd($termDate, $request->all());
        $protocol->update([
            'date' => $datedate,
            'description' => $request->input('description'),
            'term' =>  $termDate,
            'people_id' => $request->input('people_id')['id']
        ]);
    }

    public function delete($id)
    {
        $protocol = Protocol::findOrFail($id);
        $protocol->delete();
    }
}
