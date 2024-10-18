// To parse this data:
//
//   import { Convert } from "./file";
//
//   const bookingChart = Convert.toBookingChart(json);

export interface BookingChart {
    id_stadium:    number;
    label: string; // Assuming you have a label property
    month:         string;
    booking_count: number;
    total_members: number;
    total_stadiums: number;
    total_numbers:  number;
    total_bookinguse: number;
    booking_total: number;
    total_bookings: number; // Change from totalBookings to total_bookings
    total_booking_use: number;
   
    bookings: number; // Total bookings
    usage: number; // Total usage
    stadium_name: string; // Add this line to include stadium name // Change from totalBookingUse to total_booking_use
    type: string; // ประเภทสนาม
    count: number; // จำนวน
}

// Converts JSON strings to/from your types
export class Convert {
    public static toBookingChart(json: string): BookingChart[] {
        return JSON.parse(json);
    }

    public static bookingChartToJson(value: BookingChart[]): string {
        return JSON.stringify(value);
    }
}
