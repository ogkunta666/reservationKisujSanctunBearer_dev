<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index(Request $request){
        $user = $request->user();
        if ($user->isAdmin){
        $reservations = Reservation::all();
        } else {
            $reservations = Reservation::where('user_id', $user->id)->get();
        }

        return response()->json($reservations, 200);
    }

    public function show(Request $request, $id){
        $user = $request->user();
        $reservation = Reservation::findOrFail($id);
        
        if(!$user->is_admin && $reservation -> user_id!=$user->id){
            return response()->json(['message' => 'Nincs jogosultságod megtekinteni ezt a foglalást!'], 403);
        }
        
        return response()->json($reservation, 200);
        
        return response()->json(['message' => 'Hiba! A foglalás nem található!'], 404);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'reservation_time' => 'required|date',
            'guests' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);
        $reservation = Reservation::create([
            'user_id' => $request->user()->id,
            'reservation_time' => $validated['reservation_time'],
            'guests' => $validated['guests'],
            'note' => $validated['note'] ?? null,
        ]);
        return response()->json($reservation, 201);
    }

    public function update(Request $request, $id){
        $user = $request->user();
        $reservation = Reservation::findOrFail($id);
        
        if(!$user->is_admin && $reservation -> user_id!=$user->id){
            return response()->json(['message' => 'Nincs jogosultságod módosítani ezt a foglalást!'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email',
            'reservation_time' => 'sometimes|required|date',
            'guests' => 'sometimes|required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        $reservation -> update($validated);
        return response()->json($reservation, 200);
    }

    public function destroy(Request $request, $id){
        $user = $request->user();
        $reservation = Reservation::findOrFail($id);

        if(!$user->is_admin && $reservation -> user_id!=$user->id){
            return response()->json(['message' => 'Nincs jogosultságod törölni ezt a foglalást!'], 403);
        }

        if ($reservation){
            $reservation->delete();
            return response()->json(['message'=>'Foglalás törölve.'],200);
        }
        
        return response()->json(['message'=>'Hiba! A foglalás nem található!'], 404);

    }


}
