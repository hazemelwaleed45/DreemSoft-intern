<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Consultant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'password', 'phone', 'age', 'title', 'major', 'country', 'image', 'bio'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function exceptions()
    {
        return $this->hasMany(Exception::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }


    

    

    /*public function getAllSlots($consultantId)
    {
        $rawQuery = "
            WITH RECURSIVE HourlySlots AS (
                SELECT
                    sc.id AS schedule_id,
                    sc.consultant_id,
                    sc.day,
                    DAYNAME(sc.day) AS day_name,
                    sc.`from` AS slot_start,
                    DATE_ADD(sc.`from`, INTERVAL 1 HOUR) AS slot_end
                FROM
                    schedules sc
                WHERE
                    sc.consultant_id = :consultantId
                UNION ALL
                SELECT
                    sc.id AS schedule_id,
                    sc.consultant_id,
                    sc.day,
                    DAYNAME(sc.day) AS day_name,
                    DATE_ADD(h.slot_start, INTERVAL 1 HOUR) AS slot_start,
                    DATE_ADD(h.slot_end, INTERVAL 1 HOUR) AS slot_end
                FROM
                    HourlySlots h
                JOIN schedules sc ON h.schedule_id = sc.id
                WHERE
                    h.slot_end < sc.`to`
            )
            SELECT
                day_name,
                slot_start,
                slot_end,
                CASE
                    WHEN e.status = 1 OR (
                        (hs.slot_start >= e.`from` AND hs.slot_start < e.`to`) OR
                        (hs.slot_end > e.`from` AND hs.slot_end <= e.`to`) OR
                        (hs.slot_start <= e.`from` AND hs.slot_end >= e.`to`)
                    ) THEN 'Unavailable'
                    WHEN b.id IS NOT NULL THEN 'Booked'
                    ELSE 'Available'
                END AS slot_status
            FROM
                HourlySlots hs
            LEFT JOIN exceptions e ON hs.consultant_id = e.consultant_id AND hs.day = e.day
            LEFT JOIN bookings b ON hs.consultant_id = b.consultant_id AND hs.day = b.day AND hs.slot_start = b.`from` AND hs.slot_end = b.`to`
            ORDER BY
                day_name, hs.slot_start
        ";

        $slots = DB::select($rawQuery, ['consultantId' => $consultantId]);

        
        $formattedSlots = collect($slots)->map(function ($slot) {
            unset($slot->schedule_id);
            return $slot;
        })->all();

        return $formattedSlots;
    }*/

    // public function getAllSlots($consultantId)
    // {

        
    //     $rawQuery = "
    //         WITH RECURSIVE HourlySlots AS (
    //             SELECT
    //                 sc.id AS schedule_id,
    //                 sc.consultant_id,
    //                 CASE 
    //                     WHEN sc.day = 'Monday' THEN DATE_ADD(CURDATE(), INTERVAL (0 - WEEKDAY(CURDATE())) DAY)
    //                     WHEN sc.day = 'Tuesday' THEN DATE_ADD(CURDATE(), INTERVAL (1 - WEEKDAY(CURDATE())) DAY)
    //                     WHEN sc.day = 'Wednesday' THEN DATE_ADD(CURDATE(), INTERVAL (2 - WEEKDAY(CURDATE())) DAY)
    //                     WHEN sc.day = 'Thursday' THEN DATE_ADD(CURDATE(), INTERVAL (3 - WEEKDAY(CURDATE())) DAY)
    //                     WHEN sc.day = 'Friday' THEN DATE_ADD(CURDATE(), INTERVAL (4 - WEEKDAY(CURDATE())) DAY)
    //                     WHEN sc.day = 'Saturday' THEN DATE_ADD(CURDATE(), INTERVAL (5 - WEEKDAY(CURDATE())) DAY)
    //                     WHEN sc.day = 'Sunday' THEN DATE_ADD(CURDATE(), INTERVAL (6 - WEEKDAY(CURDATE())) DAY)
    //                 END AS day,
    //                 sc.`from` AS slot_start,
    //                 DATE_ADD(sc.`from`, INTERVAL 1 HOUR) AS slot_end
    //             FROM schedules sc
    //             WHERE sc.consultant_id = :consultantId
    //             UNION ALL
    //             SELECT
    //                 h.schedule_id,
    //                 h.consultant_id,
    //                 h.day,
    //                 DATE_ADD(h.slot_start, INTERVAL 1 HOUR) AS slot_start,
    //                 DATE_ADD(h.slot_end, INTERVAL 1 HOUR) AS slot_end
    //             FROM HourlySlots h
    //             JOIN schedules sc ON h.schedule_id = sc.id
    //             WHERE h.slot_end < sc.`to`
    //         )
    //         SELECT 
    //             hs.schedule_id,
    //             hs.day,
    //             hs.slot_start,
    //             hs.slot_end,
    //             CASE
    //                 WHEN hs.day < CURDATE() OR (hs.day = CURDATE() AND hs.slot_end < CURRENT_TIME()) THEN 'Passed'
    //                 WHEN e.status = 1 OR (
    //                     (hs.slot_start >= e.`from` AND hs.slot_start < e.`to`) OR 
    //                     (hs.slot_end > e.`from` AND hs.slot_end <= e.`to`) OR 
    //                     (hs.slot_start <= e.`from` AND hs.slot_end >= e.`to`)
    //                 ) THEN 'Unavailable'
    //                 -- Check for bookings
    //                 WHEN b.id IS NOT NULL THEN 'Booked'
    //                 -- Mark as available
    //                 ELSE 'Available'
    //             END AS slot_status
    //         FROM HourlySlots hs
    //         LEFT JOIN exceptions e ON hs.consultant_id = e.consultant_id AND hs.day = e.day
    //         LEFT JOIN bookings b ON hs.consultant_id = b.consultant_id AND hs.day = b.day 
    //                             AND hs.slot_start = b.`from` AND hs.slot_end = b.`to`
    //         ORDER BY hs.day, hs.slot_start
    //     ";

        
    //     $slots = DB::select($rawQuery, ['consultantId' => $consultantId]);

        
    //     $formattedSlots = collect($slots)->map(function ($slot) {
    //         unset($slot->schedule_id); 
    //         return $slot;
    //     })->all();

    //     return $formattedSlots;
    // }
// ////////////////////////////////////////////////////////////////////////
    // public function getAllSlots($consultantId)
    // {
    //     $rawQuery = "
    //         WITH RECURSIVE HourlySlots AS (
    //             SELECT
    //                 sc.id AS schedule_id,
    //                 sc.consultant_id,
    //                 CASE 
    //                     WHEN sc.day = 'Monday' THEN DATE_ADD(CURDATE(), INTERVAL (0 - WEEKDAY(CURDATE())) DAY)
    //                     WHEN sc.day = 'Tuesday' THEN DATE_ADD(CURDATE(), INTERVAL (1 - WEEKDAY(CURDATE())) DAY)
    //                     WHEN sc.day = 'Wednesday' THEN DATE_ADD(CURDATE(), INTERVAL (2 - WEEKDAY(CURDATE())) DAY)
    //                     WHEN sc.day = 'Thursday' THEN DATE_ADD(CURDATE(), INTERVAL (3 - WEEKDAY(CURDATE())) DAY)
    //                     WHEN sc.day = 'Friday' THEN DATE_ADD(CURDATE(), INTERVAL (4 - WEEKDAY(CURDATE())) DAY)
    //                     WHEN sc.day = 'Saturday' THEN DATE_ADD(CURDATE(), INTERVAL (5 - WEEKDAY(CURDATE())) DAY)
    //                     WHEN sc.day = 'Sunday' THEN DATE_ADD(CURDATE(), INTERVAL (6 - WEEKDAY(CURDATE())) DAY)
    //                 END AS day,
    //                 sc.`from` AS slot_start,
    //                 DATE_ADD(sc.`from`, INTERVAL 1 HOUR) AS slot_end
    //             FROM schedules sc
    //             WHERE sc.consultant_id = :consultantId
    //             UNION ALL
    //             SELECT
    //                 h.schedule_id,
    //                 h.consultant_id,
    //                 h.day,
    //                 DATE_ADD(h.slot_start, INTERVAL 1 HOUR) AS slot_start,
    //                 DATE_ADD(h.slot_end, INTERVAL 1 HOUR) AS slot_end
    //             FROM HourlySlots h
    //             JOIN schedules sc ON h.schedule_id = sc.id
    //             WHERE h.slot_end < sc.`to`
    //         )
    //         SELECT 
    //             hs.day,
    //             hs.slot_start,
    //             hs.slot_end,
    //             CASE
    //                 WHEN hs.day < CURDATE() OR (hs.day = CURDATE() AND hs.slot_end < CURRENT_TIME()) THEN 'Passed'
    //                 WHEN e.status = 1 OR (
    //                     (hs.slot_start >= e.`from` AND hs.slot_start < e.`to`) OR 
    //                     (hs.slot_end > e.`from` AND hs.slot_end <= e.`to`) OR 
    //                     (hs.slot_start <= e.`from` AND hs.slot_end >= e.`to`)
    //                 ) THEN 'Unavailable'
    //                 WHEN b.id IS NOT NULL THEN 'Booked'
    //                 ELSE 'Available'
    //             END AS slot_status
    //         FROM HourlySlots hs
    //         LEFT JOIN exceptions e ON hs.consultant_id = e.consultant_id AND hs.day = e.day
    //         LEFT JOIN bookings b ON hs.consultant_id = b.consultant_id AND hs.day = b.day 
    //                             AND hs.slot_start = b.`from` AND hs.slot_end = b.`to`
    //         ORDER BY hs.day, hs.slot_start
    //     ";

    //     $slots = DB::select($rawQuery, ['consultantId' => $consultantId]);

    //     // Group the slots by day
    //     $groupedSlots = collect($slots)->groupBy('day')->map(function ($slots, $day) {
    //         return [
    //             'day' => $day,
    //             'slots' => $slots->map(function ($slot) {
    //                 return [
    //                     'slot_start' => $slot->slot_start,
    //                     'slot_end' => $slot->slot_end,
    //                     'slot_status' => $slot->slot_status,
    //                 ];
    //             })->all()
    //         ];
    //     })->values()->all();

    //     return $groupedSlots;
    // }
// //////////////////////////////////////////////////////////////










public function getWeeklySlots($consultantId)
{
    $currentDate = now();
    $weekDays = collect(range(0, 6))->map(function ($offset) use ($currentDate) {
        return $currentDate->copy()->addDays($offset)->format('l'); 
    })->toArray();


    
    $schedules = Schedule::where('consultant_id', $consultantId)->get();
  
    $bookings = Booking::where('consultant_id', $consultantId)
        ->whereBetween('day', [$currentDate->startOfDay(), $currentDate->copy()->addDays(7)->endOfDay()])
        ->get()
        ->groupBy(function ($booking) {
            return Carbon::parse($booking->day)->toDateString(); 
        });

    $exceptions = Exception::where('consultant_id', $consultantId)
        ->whereBetween('day', [$currentDate->startOfDay(), $currentDate->copy()->addDays(7)->endOfDay()])
        ->get()
        ->groupBy(function ($exception) {
            return Carbon::parse($exception->day)->toDateString(); 
        });

    
    $groupedSlots = $schedules->map(function ($schedule) use ($currentDate, $bookings, $exceptions, $weekDays) {
        $dayIndex = array_search($schedule->day, $weekDays);
        $scheduleDate = $currentDate->copy()->startOfDay()->addDays($dayIndex)->toDateString();

        if (Carbon::parse($scheduleDate)->lt($currentDate->startOfDay())) {
            return null;
        }

        $slots = collect();
        $startTime = Carbon::parse($schedule->from);
        $endTime = Carbon::parse($schedule->to);

    
        while ($startTime < $endTime) {
            $slotEndTime = $startTime->copy()->addHour();

            $slotStatus = 'Available'; 

           
            $bookingForSlot = $bookings->get($scheduleDate)?->firstWhere(function ($booking) use ($startTime, $slotEndTime) {
                return $startTime >= Carbon::parse($booking->from) && $slotEndTime <= Carbon::parse($booking->to);
            });

            $exceptionForSlot = $exceptions->get($scheduleDate)?->firstWhere(function ($exception) use ($startTime, $slotEndTime) {
                return $startTime >= Carbon::parse($exception->from) && $slotEndTime <= Carbon::parse($exception->to);
            });

            
            if ($exceptionForSlot && $exceptionForSlot->status == 0) {
                $slotStatus = 'Unavailable'; 
            } elseif ($exceptionForSlot && $exceptionForSlot->status == 2) {
                $slotStatus = 'Unavailable'; 
            }
            
            elseif ($bookingForSlot) {
                $slotStatus = 'Booked';
            }

            
            $slots->push([
                'slot_start' => $startTime->format('H:i:s'),
                'slot_end' => $slotEndTime->format('H:i:s'),
                'slot_status' => $slotStatus,
            ]);

            
            $startTime = $slotEndTime;
        }

        
        $dayStatus = 1;  
        if ($exceptions->get($scheduleDate)?->contains('status', 1)) {
            $dayStatus = 2; 
        }

        return [
            'day' => $scheduleDate,
            'status' => $dayStatus,
            'slots' => $slots,
        ];
    })->filter()->keyBy(function ($item) {
        return $item['day'];  
    });

    
    $sevenDays = collect(range(0, 6))->mapWithKeys(function ($offset) use ($currentDate, $groupedSlots) {
        $date = $currentDate->copy()->addDays($offset)->toDateString();

        return [
            $date => $groupedSlots->get($date) ?? [
                'day' => $date,
                'status' => 0,
                'slots' => [
                    [
                        'slot_start' => null,
                        'slot_end' => null,
                        'slot_status' => 'no_schedule',
                    ]
                ]
            ]
        ];
    });

    return $sevenDays->all();
}



// public function getWeeklySlotsbydate($consultantId, $startDate = null)
// {

//     $currentDate = $startDate ? Carbon::parse($startDate) : now();
    
//     $weekDays = collect(range(0, 6))->map(function ($offset) use ($currentDate) {
//         return $currentDate->copy()->addDays($offset)->format('l'); 
//     })->toArray();

//     $schedules = Schedule::where('consultant_id', $consultantId)->get();

//     $bookings = Booking::where('consultant_id', $consultantId)
//         ->whereBetween('day', [$currentDate->startOfDay(), $currentDate->copy()->addDays(7)->endOfDay()])
//         ->get()
//         ->groupBy(function ($booking) {
//             return Carbon::parse($booking->day)->toDateString(); 
//         });

//     $exceptions = Exception::where('consultant_id', $consultantId)
//         ->whereBetween('day', [$currentDate->startOfDay(), $currentDate->copy()->addDays(7)->endOfDay()])
//         ->get()
//         ->groupBy(function ($exception) {
//             return Carbon::parse($exception->day)->toDateString(); 
//         });

//     $groupedSlots = $schedules->map(function ($schedule) use ($currentDate, $bookings, $exceptions, $weekDays) {
//         $dayIndex = array_search($schedule->day, $weekDays);
//         $scheduleDate = $currentDate->copy()->startOfDay()->addDays($dayIndex)->toDateString();

//         if (Carbon::parse($scheduleDate)->lt($currentDate->startOfDay())) {
//             return null;
//         }

//         $slots = collect();
//         $startTime = Carbon::parse($schedule->from);
//         $endTime = Carbon::parse($schedule->to);

//         while ($startTime < $endTime) {
//             $slotEndTime = $startTime->copy()->addHour();
//             $slotStatus = 'Available'; 

//             $bookingForSlot = $bookings->get($scheduleDate)?->firstWhere(function ($booking) use ($startTime, $slotEndTime) {
//                 return $startTime >= Carbon::parse($booking->from) && $slotEndTime <= Carbon::parse($booking->to);
//             });

//             $exceptionForSlot = $exceptions->get($scheduleDate)?->firstWhere(function ($exception) use ($startTime, $slotEndTime) {
//                 return $startTime >= Carbon::parse($exception->from) && $slotEndTime <= Carbon::parse($exception->to);
//             });

//             if ($exceptionForSlot && $exceptionForSlot->status == 0) {
//                 $slotStatus = 'Unavailable'; 
//             } 
//             elseif ($bookingForSlot) {
//                 $slotStatus = 'Booked';
//             }

//             $slots->push([
//                 'slot_start' => $startTime->format('H:i:s'),
//                 'slot_end' => $slotEndTime->format('H:i:s'),
//                 'slot_status' => $slotStatus,
//             ]);

//             $startTime = $slotEndTime;
//         }

//         $dayStatus = 1;  
//         if ($exceptions->get($scheduleDate)?->contains('status', 1)) {
//             $dayStatus = 2; 
//         }

//         return [
//             'day' => $scheduleDate,
//             'status' => $dayStatus,
//             'slots' => $slots,
//         ];
//     })->filter()->keyBy(function ($item) {
//         return $item['day'];  
//     });

//     $sevenDays = collect(range(0, 6))->mapWithKeys(function ($offset) use ($currentDate, $groupedSlots) {
//         $date = $currentDate->copy()->addDays($offset)->toDateString();

//         return [
//             $date => $groupedSlots->get($date) ?? [
//                 'day' => $date,
//                 'status' => 0,
//                 'slots' => [
//                     [
//                         'slot_start' => null,
//                         'slot_end' => null,
//                         'slot_status' => 'no_schedule',
//                     ]
//                 ]
//             ]
//         ];
//     });

//     return $sevenDays->all();
// }



public function getWeeklySlotsByDate($consultantId, $startDate = null)
{
    // Initialize the start date
    $currentDate = $startDate ? Carbon::parse($startDate) : now();

    // Prepare weekdays from the start date
    $weekDays = collect(range(0, 6))->map(function ($offset) use ($currentDate) {
        return $currentDate->copy()->addDays($offset)->format('l');
    })->toArray();

    // Fetch schedules for the consultant
    $schedules = Schedule::where('consultant_id', $consultantId)->get();

    // Fetch bookings grouped by date
    $bookings = Booking::where('consultant_id', $consultantId)
        ->whereBetween('day', [$currentDate->startOfDay(), $currentDate->copy()->addDays(7)->endOfDay()])
        ->get()
        ->groupBy(function ($booking) {
            return Carbon::parse($booking->day)->toDateString();
        });

    // Fetch exceptions grouped by date
    $exceptions = Exception::where('consultant_id', $consultantId)
        ->whereBetween('day', [$currentDate->startOfDay(), $currentDate->copy()->addDays(7)->endOfDay()])
        ->get()
        ->groupBy(function ($exception) {
            return Carbon::parse($exception->day)->toDateString();
        });

    // Process each schedule and map slots
    $groupedSlots = $schedules->map(function ($schedule) use ($currentDate, $bookings, $exceptions, $weekDays) {
        // Calculate the schedule's date
        $dayIndex = array_search($schedule->day, $weekDays);
        $scheduleDate = $currentDate->copy()->startOfDay()->addDays($dayIndex)->toDateString();

        // Skip past dates
        if (Carbon::parse($scheduleDate)->lt($currentDate->startOfDay())) {
            return null;
        }

        $slots = collect();
        $startTime = Carbon::parse($schedule->from);
        $endTime = Carbon::parse($schedule->to);

        // Generate hourly slots
        while ($startTime < $endTime) {
            $slotEndTime = $startTime->copy()->addHour();
            $slotStatus = 'Available';

            // Check if the slot is booked or unavailable
            $bookingForSlot = $bookings->get($scheduleDate)?->firstWhere(function ($booking) use ($startTime, $slotEndTime) {
                return $startTime >= Carbon::parse($booking->from) && $slotEndTime <= Carbon::parse($booking->to);
            });

            $exceptionForSlot = $exceptions->get($scheduleDate)?->firstWhere(function ($exception) use ($startTime, $slotEndTime) {
                return $startTime >= Carbon::parse($exception->from) && $slotEndTime <= Carbon::parse($exception->to);
            });

            if ($exceptionForSlot && $exceptionForSlot->status == 0) {
                $slotStatus = 'Unavailable';
            } elseif ($bookingForSlot) {
                $slotStatus = 'Booked';
            }

            $slots->push([
                'slot_start' => $startTime->format('H:i:s'),
                'slot_end' => $slotEndTime->format('H:i:s'),
                'slot_status' => $slotStatus,
            ]);

            $startTime = $slotEndTime;
        }

        // Determine day status
        $dayStatus = 1; // Default: normal day
        if ($exceptions->get($scheduleDate)?->contains('status', 1)) {
            $dayStatus = 2; // Exceptionally unavailable day
        }

        // Override all slots if the day status is 2
        if ($dayStatus == 2) {
            $slots = $slots->map(function ($slot) {
                return [
                    'slot_start' => $slot['slot_start'],
                    'slot_end' => $slot['slot_end'],
                    'slot_status' => 'Unavailable',
                ];
            });
        }

        return [
            'day' => $scheduleDate,
            'status' => $dayStatus,
            'slots' => $slots,
        ];
    })->filter()->keyBy(function ($item) {
        return $item['day'];
    });

    // Fill missing days in the week with no schedules
    $sevenDays = collect(range(0, 6))->mapWithKeys(function ($offset) use ($currentDate, $groupedSlots) {
        $date = $currentDate->copy()->addDays($offset)->toDateString();

        return [
            $date => $groupedSlots->get($date) ?? [
                'day' => $date,
                'status' => 0, // No schedule
                'slots' => [
                    [
                        'slot_start' => null,
                        'slot_end' => null,
                        'slot_status' => 'no_schedule',
                    ],
                ],
            ],
        ];
    });

    return $sevenDays->all();
}













public function getAllSlots($consultantId)
    {
        $rawQuery = "
            WITH RECURSIVE HourlySlots AS (
                SELECT
                    sc.id AS schedule_id,
                    sc.consultant_id,
                    CASE 
                        WHEN sc.day = 'Monday' THEN DATE_ADD(CURDATE(), INTERVAL (0 - WEEKDAY(CURDATE())) DAY)
                        WHEN sc.day = 'Tuesday' THEN DATE_ADD(CURDATE(), INTERVAL (1 - WEEKDAY(CURDATE())) DAY)
                        WHEN sc.day = 'Wednesday' THEN DATE_ADD(CURDATE(), INTERVAL (2 - WEEKDAY(CURDATE())) DAY)
                        WHEN sc.day = 'Thursday' THEN DATE_ADD(CURDATE(), INTERVAL (3 - WEEKDAY(CURDATE())) DAY)
                        WHEN sc.day = 'Friday' THEN DATE_ADD(CURDATE(), INTERVAL (4 - WEEKDAY(CURDATE())) DAY)
                        WHEN sc.day = 'Saturday' THEN DATE_ADD(CURDATE(), INTERVAL (5 - WEEKDAY(CURDATE())) DAY)
                        WHEN sc.day = 'Sunday' THEN DATE_ADD(CURDATE(), INTERVAL (6 - WEEKDAY(CURDATE())) DAY)
                    END AS day,
                    sc.`from` AS slot_start,
                    DATE_ADD(sc.`from`, INTERVAL 1 HOUR) AS slot_end
                FROM schedules sc
                WHERE sc.consultant_id = :consultantId
                UNION ALL
                SELECT
                    h.schedule_id,
                    h.consultant_id,
                    h.day,
                    DATE_ADD(h.slot_start, INTERVAL 1 HOUR) AS slot_start,
                    DATE_ADD(h.slot_end, INTERVAL 1 HOUR) AS slot_end
                FROM HourlySlots h
                JOIN schedules sc ON h.schedule_id = sc.id
                WHERE h.slot_end < sc.`to`
            )
            SELECT 
                hs.day,
                hs.slot_start,
                hs.slot_end,
                CASE
                    WHEN hs.day < CURDATE() OR (hs.day = CURDATE() AND hs.slot_end < CURRENT_TIME()) THEN 'Passed'
                    WHEN e.status = 1 OR (
                        (hs.slot_start >= e.`from` AND hs.slot_start < e.`to`) OR 
                        (hs.slot_end > e.`from` AND hs.slot_end <= e.`to`) OR 
                        (hs.slot_start <= e.`from` AND hs.slot_end >= e.`to`)
                    ) THEN 'Unavailable'
                    WHEN b.id IS NOT NULL THEN 'Booked'
                    ELSE 'Available'
                END AS slot_status
            FROM HourlySlots hs
            LEFT JOIN exceptions e ON hs.consultant_id = e.consultant_id AND hs.day = e.day
            LEFT JOIN bookings b ON hs.consultant_id = b.consultant_id AND hs.day = b.day 
                                AND hs.slot_start = b.`from` AND hs.slot_end = b.`to`
            ORDER BY hs.day, hs.slot_start
        ";

        $slots = DB::select($rawQuery, ['consultantId' => $consultantId]);

        $groupedSlots = collect($slots)->groupBy('day')->map(function ($slots, $day) {
            return [
                'day' => $day,
                'status'=> 1 ,
                'slots' => $slots->map(function ($slot) {
                    return [
                        'slot_start' => $slot->slot_start,
                        'slot_end' => $slot->slot_end,
                        'slot_status' => $slot->slot_status,
                    ];
                })->all()
            ];
        })->keyBy('day'); 

        
        $sevenDays = collect(range(0, 6))->map(function ($offset) {
            return now()->addDays($offset)->toDateString();
        });

        
        $finalSlots = $sevenDays->map(function ($date) use ($groupedSlots) {
            if ($groupedSlots->has($date)) {
                return $groupedSlots->get($date); 
            }
            return [
                'day' => $date,
                'status'=> 0,
                'slots' => [
                    [
                        'slot_start' => null,
                        'slot_end' => null,
                        'slot_status' => 'no_schedule'
                    ]
                ]
            ];
        });

        return $finalSlots->values()->all();
    }

   
    public function getSlotsForDay($consultantId, $date)
{
    $formattedDate = \Carbon\Carbon::parse($date)->format('Y-m-d');

    $rawQuery = "
        WITH RECURSIVE HourlySlots AS (
            SELECT
                sc.id AS schedule_id,
                sc.consultant_id,
                CASE 
                    WHEN sc.day = 'Monday' THEN DATE_ADD(:date, INTERVAL (0 - WEEKDAY(:date)) DAY)
                    WHEN sc.day = 'Tuesday' THEN DATE_ADD(:date, INTERVAL (1 - WEEKDAY(:date)) DAY)
                    WHEN sc.day = 'Wednesday' THEN DATE_ADD(:date, INTERVAL (2 - WEEKDAY(:date)) DAY)
                    WHEN sc.day = 'Thursday' THEN DATE_ADD(:date, INTERVAL (3 - WEEKDAY(:date)) DAY)
                    WHEN sc.day = 'Friday' THEN DATE_ADD(:date, INTERVAL (4 - WEEKDAY(:date)) DAY)
                    WHEN sc.day = 'Saturday' THEN DATE_ADD(:date, INTERVAL (5 - WEEKDAY(:date)) DAY)
                    WHEN sc.day = 'Sunday' THEN DATE_ADD(:date, INTERVAL (6 - WEEKDAY(:date)) DAY)
                END AS schedule_day,
                sc.day AS day_name,
                sc.`from` AS slot_start,
                DATE_ADD(sc.`from`, INTERVAL 1 HOUR) AS slot_end,
                sc.`to` AS schedule_end
            FROM
                schedules sc
            WHERE
                sc.consultant_id = :consultantId
                AND sc.day = DAYNAME(:date)
            UNION ALL
            SELECT
                h.schedule_id,
                h.consultant_id,
                h.schedule_day,
                h.day_name,
                DATE_ADD(h.slot_start, INTERVAL 1 HOUR) AS slot_start,
                DATE_ADD(h.slot_end, INTERVAL 1 HOUR) AS slot_end,
                h.schedule_end
            FROM
                HourlySlots h
            WHERE
                DATE_ADD(h.slot_start, INTERVAL 1 HOUR) < h.schedule_end
        )
        SELECT
            day_name,
            slot_start,
            slot_end,
            CASE
                WHEN NOW() >= slot_end THEN 'Passed'
                WHEN EXISTS (
                    SELECT 1
                    FROM exceptions e
                    WHERE e.consultant_id = :consultantId
                    AND e.day = schedule_day
                    AND (
                        (slot_start >= e.`from` AND slot_start < e.`to`) OR
                        (slot_end > e.`from` AND slot_end <= e.`to`) OR
                        (slot_start <= e.`from` AND slot_end >= e.`to`)
                    )
                ) THEN 'Unavailable'
                WHEN EXISTS (
                    SELECT 1
                    FROM bookings b
                    WHERE b.consultant_id = :consultantId
                    AND b.day = schedule_day
                    AND b.`from` = slot_start
                    AND b.`to` = slot_end
                ) THEN 'Booked'
                ELSE 'Available'
            END AS slot_status
        FROM
            HourlySlots
        ORDER BY
            slot_start
    ";

    // Bindings array
    $bindings = array_merge(
        array_fill(0, 7, $formattedDate), // 7 occurrences for the CASE statement
        [$consultantId, $date], // Consultant ID and date for base case
        [$consultantId, $consultantId] // Consultant ID for exceptions and bookings
    );

    $slots = DB::select($rawQuery, $bindings);

    $formattedSlots = collect($slots)->map(function ($slot) {
        unset($slot->schedule_id);
        return $slot;
    })->all();

    return $formattedSlots;
}

    
    
    
    

    
// public function getSlots($consultantId, $date)
// {
//     $rawQuery = "
//         WITH RECURSIVE HourlySlots AS (
//             SELECT
//                 sc.id AS schedule_id,
//                 sc.consultant_id,
//                 sc.day,
//                 DAYNAME(sc.day) AS day_name,
//                 sc.`from` AS slot_start,
//                 DATE_ADD(sc.`from`, INTERVAL 1 HOUR) AS slot_end
//             FROM
//                 schedules sc
//             WHERE
//                 sc.consultant_id = ?
//                 AND sc.day = ?
//             UNION ALL
//             SELECT
//                 sc.id AS schedule_id,
//                 sc.consultant_id,
//                 sc.day,
//                 DAYNAME(sc.day) AS day_name,
//                 DATE_ADD(h.slot_start, INTERVAL 1 HOUR) AS slot_start,
//                 DATE_ADD(h.slot_end, INTERVAL 1 HOUR) AS slot_end
//             FROM
//                 HourlySlots h
//             JOIN schedules sc ON h.schedule_id = sc.id
//             WHERE
//                 h.slot_end < sc.`to`
//         )
//         SELECT
//             day_name,
//             slot_start,
//             slot_end,
//             CASE
//                 WHEN e.status = 1 OR (
//                     (hs.slot_start >= e.`from` AND hs.slot_start < e.`to`) OR
//                     (hs.slot_end > e.`from` AND hs.slot_end <= e.`to`) OR
//                     (hs.slot_start <= e.`from` AND hs.slot_end >= e.`to`)
//                 ) THEN 'Unavailable'
//                 WHEN b.id IS NOT NULL THEN 'Booked'
//                 ELSE 'Available'
//             END AS slot_status
//         FROM
//             HourlySlots hs
//         LEFT JOIN exceptions e ON hs.consultant_id = e.consultant_id AND hs.day = e.day
//         LEFT JOIN bookings b ON hs.consultant_id = b.consultant_id AND hs.day = b.day AND hs.slot_start = b.`from` AND hs.slot_end = b.`to`
//         ORDER BY
//             day_name, hs.slot_start
//     ";

//     $slots = DB::select($rawQuery, [$consultantId, $date]);

    
//     $formattedSlots = collect($slots)->map(function ($slot) {
//         unset($slot->schedule_id);
//         return $slot;
//     })->all();

//     return $formattedSlots;
// }
    
}
