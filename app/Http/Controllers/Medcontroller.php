<?php

namespace App\Http\Controllers;

use App\Models\MedTracker;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MedController extends Controller
{

    public function index()
    {
        // Haal de laatste medicatie-informatie op
        $medTracker = MedTracker::latest('created_at')->first();

        // Als er geen gegevens zijn, zetten we default waarden
        $last_dose = $medTracker ? $medTracker->last_dose : null;
        $next_dose = $medTracker ? $medTracker->next_dose : null;

        // Geef de gegevens door aan de view
        return view('welcome', compact('last_dose', 'next_dose'));
    }
    public function MedsCheck(Request $request)
    {
        // Zorg ervoor dat meds_taken boolean is (indien checkbox is geselecteerd)
        $meds_taken = $request->has('meds_taken');  // De waarde is TRUE als de checkbox is aangevinkt, anders FALSE

        // Maak een nieuwe MedTracker record aan
        $medTracker = new MedTracker();

        $medTracker->last_dose = Carbon::now()->addHours(1);

        $medTracker->meds_taken = $meds_taken;

        // Bereken next_dose als last_dose + 4 uur
        $medTracker->next_dose = $medTracker->last_dose->copy()->addHours(4);

        $medTracker->save();

        return redirect('/')->with('success', 'gegevens succesvol opgeslagen!');
    }
}
