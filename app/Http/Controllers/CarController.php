<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::all();
        return view('cars.index', compact('cars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $car = Car::create([
            'name' => $request->name
        ]);

        return response()->json($car);
    }

    public function update(Request $request, Car $car)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $car->update([
            'name' => $request->name
        ]);

        return response()->json($car);
    }

    public function destroy(Car $car)
    {
        $car->delete();
        return response()->json(['success' => true]);
    }
}
