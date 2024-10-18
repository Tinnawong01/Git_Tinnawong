// To parse this data:
//
//   import { Convert } from "./file";
//
//   const bookingCount = Convert.toBookingCount(json);

export interface BookingCount {
    count:        number;
    id_booking:   number;
    booking_date: Date;
    stadium_name: string;
    number_name:  string;
    time:         string;
    id_time:      number;
}

// Converts JSON strings to/from your types
export class Convert {
    public static toBookingCount(json: string): BookingCount[] {
        return JSON.parse(json, (key, value) => {
            if (key === 'booking_date') {
                return new Date(value);
            }
            return value;
        });
    }

    public static bookingCountToJson(value: BookingCount[]): string {
        return JSON.stringify(value, (key, val) => {
            if (key === 'booking_date') {
                return val.toISOString(); // or any other date format you prefer
            }
            return val;
        });
    }
}
