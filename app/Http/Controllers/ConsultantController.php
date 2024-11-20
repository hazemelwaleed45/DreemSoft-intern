<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;


class ConsultantController extends Controller
{
   

    public function getConsultantDetails($consultantId , $date)
    {
        
        // dd($consultantId , $date);
        $consultant = Consultant::findOrFail($consultantId);

        // dd($consultant);
       
       
        $slots = (new Consultant)->getAllSlots($consultantId);

        $daySlot=[];
        $sevenDays = [];
        $startDate = Carbon::now();

        for ($i = 0; $i < 7; $i++) {
            $sevenDays[] = $startDate->copy()->addDays($i)->toDateString();
        }

        foreach ($slots as $slot) {
           
        }
        // dd($slots);
        // Return both the consultant details and the slots in the response
        return response()->json([
            'consultant' => [
                'name' => $consultant->name,
                'image' => $consultant->image,
                'title' => $consultant->title,
                'bio' => $consultant->bio
            ],
            
            'slots' => $slots
        ]);
    }


    public function GETSLOTS($consultantId )
    {
        
        // dd($consultantId );
        $consultant = Consultant::findOrFail($consultantId);

        // dd($consultant);

        $slots = (new Consultant)->getWeeklySlots($consultantId);

        // dd($slots);
        
        return response()->json([
            'consultant' => [
                'name' => $consultant->name,
                'image' => $consultant->image,
                'title' => $consultant->title,
                'bio' => $consultant->bio
            ],
            
            'slots' => $slots
        ]);
    }
    public function getConsultantAllSlots($consultantId )
    {
        
        // dd($consultantId );
        $consultant = Consultant::findOrFail($consultantId);

        // dd($consultant);

        $slots = (new Consultant)->getAllSlots($consultantId);

        // dd($slots);
        
        return response()->json([
            'consultant' => [
                'name' => $consultant->name,
                'image' => $consultant->image,
                'title' => $consultant->title,
                'bio' => $consultant->bio
            ],
            
            'slots' => $slots
        ]);
    }


    public function getWeeklySlots($consultantId, Request $request)
    {

        $consultant = Consultant::findOrFail($consultantId);

        $startDate = $request->query('startDate', now()->toDateString());

        
        $slots = (new Consultant)->getWeeklySlotsbydate($consultantId, $startDate);

        return response()->json([
            'consultant' => [
                'name' => $consultant->name,
                'image' => $consultant->image,
                'title' => $consultant->title,
                'bio' => $consultant->bio
            ],
            
            'slots' => $slots
        ]);
    }


    public function index()
    {
        $users = Consultant::with('schedules')->get();

        return response()->json([
            'data' => $users ,
            'message' => 'Users retrieved successfully',
        ], 200);
    }

    public function show($id)
    {
        return Consultant::with(['schedules', 'exceptions', 'bookings'])->findOrFail($id);
    }

   
}
