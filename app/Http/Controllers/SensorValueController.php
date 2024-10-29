<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorValue;

class SensorValueController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric',
        ]);

        $sensorValue = new SensorValue();
        $sensorValue->value = $request->input('value');
        $sensorValue->save();

        return response()->json(['message' => 'Value saved successfully!'], 201);
    }
}
