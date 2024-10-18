// To parse this data:
//
//   import { Convert, ReportBooking } from "./file";
//
//   const reportBooking = Convert.toReportBooking(json);

export interface ReportBooking {
    byDate:  ByDate[];
    byMonth: ByMonth[];
}

export interface ByDate {
    count:        number;
    booking_date: Date;
    stadium_name: StadiumName;
}

export enum StadiumName {
    สนามบาสเกตบอล = "สนามบาสเกตบอล",
    สนามวอลเลย์บอล = "สนามวอลเลย์บอล",
    สนามแบดมินตัน = "สนามแบดมินตัน",
    สนามฟุตบอล = "สนามฟุตบอล",
    สนามเทนนิส = "สนามเทนนิส",
    สนามเปตอง = "สนามเปตอง",
}

export interface ByMonth {
    count:        number;
    month_year:   string;
    stadium_name: StadiumName;
}

// Converts JSON strings to/from your types
export class Convert {
    public static toReportBooking(json: string): ReportBooking {
        return JSON.parse(json);
    }

    public static reportBookingToJson(value: ReportBooking): string {
        return JSON.stringify(value);
    }
}
