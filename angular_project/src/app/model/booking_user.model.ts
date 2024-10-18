// To parse this data:
//
//   import { Convert } from "./file";
//
//   const bookingUser = Convert.toBookingUser(json);

export interface BookingUser {
    id_booking:   number;
    booking_date: Date;
    stadium_name: string;
    number_name:  string;
    time:         string;
    fname:        string;
    lname:        string;
    booking_status: number;
    id_time: number;
    id_stadium:     number;
    id_number:      number;
    id_member:number;
    role:      string; 
}

// Converts JSON strings to/from your types
export class Convert {
    public static toBookingUser(json: string): BookingUser[] {
        return JSON.parse(json);
    }

    public static bookingUserToJson(value: BookingUser[]): string {
        return JSON.stringify(value);
    }
}