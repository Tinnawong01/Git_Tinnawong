// To parse this data:
//
//   import { Convert } from "./file";
//
//   const bookingMonth = Convert.toBookingMonth(json);

export interface BookingMonth {
    count: number;
    month_year: string;
    stadium_name: string;
    number_name: string; // Add number_name property
}

// Converts JSON strings to/from your types
export class Convert {
    public static toBookingMonth(json: string): BookingMonth[] {
        return JSON.parse(json);
    }

    public static bookingMonthToJson(value: BookingMonth[]): string {
        return JSON.stringify(value);
    }
}
