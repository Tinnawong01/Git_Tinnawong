// src/app/model/booking.model.ts

export interface Bookingid {
    id_booking:           number;
    booking_date:         Date;
    present_date?:        Date; // Make optional
    present_time?:        string; // Make optional
    stadium_name:         string;
    number_name:          string;
    time:                 string;
    fname:                string;
    lname:                string;
    booking_status_label: BookingStatusLabel;
    qrCodeUrl?:           string; // Optional property for QR code URL
}

export class Convert {
    public static toBookingid(json: string): Bookingid[] {
        // ตรวจสอบว่าข้อมูล JSON ตรงกับรูปแบบ Bookingid หรือไม่
        return JSON.parse(json);
    }

    public static bookingidToJson(value: Bookingid[]): string {
        return JSON.stringify(value);
    }
}

export enum BookingStatusLabel {
    จอง = "จอง",
    เข้าใช้งาน = "เข้าใช้งาน",
}
