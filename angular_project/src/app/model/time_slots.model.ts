// To parse this data:
//
//   import { Convert } from "./file";
//
//   const timeSlots = Convert.toTimeSlots(json);

export interface TimeSlots {
    id_time_slot: number; // Add the id_time_slot property
    id_time: number;
    time:    string;
    isSelected: boolean;
    booked: boolean; // Add this property
}

// Converts JSON strings to/from your types
export class Convert {
    public static toTimeSlots(json: string): TimeSlots[] {
        return JSON.parse(json);
    }

    public static timeSlotsToJson(value: TimeSlots[]): string {
        return JSON.stringify(value);
    }
}
