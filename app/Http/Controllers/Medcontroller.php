<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\MedTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function getGraphData()
    {
        // Get the first and second dose for each day
        $medicationData = DB::table('meds_data as m1')
            ->select(
                DB::raw('DATE(m1.created_at) as date'),
                DB::raw('MIN(m1.last_dose) as first_dose'),
                DB::raw('MAX(CASE WHEN m2.last_dose > m1.last_dose THEN m2.last_dose ELSE NULL END) as second_dose')
            )
            ->join('meds_data as m2', function($join) {
                // Join on the same date and ensure we get the second dose based on ordering
                $join->on(DB::raw('DATE(m2.created_at)'), '=', DB::raw('DATE(m1.created_at)'))
                     ->whereRaw('m2.last_dose > m1.last_dose');
            })
            ->groupBy(DB::raw('DATE(m1.created_at)'))
            ->orderBy('date', 'ASC')
            ->get();
    
        return response()->json($medicationData);
    }
    
}
