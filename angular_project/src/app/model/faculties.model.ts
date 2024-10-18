// To parse this data:
//
//   import { Convert } from "./file";
//
//   const faculties = Convert.toFaculties(json);

export interface Faculties {
    Facuty_id:   number;
    Facuty_name: string;
    majors:      Major[];
}

export interface Major {
    Major_id:   string;
    Major_name: string;
}

// Converts JSON strings to/from your types
export class Convert {
    public static toFaculties(json: string): Faculties[] {
        return JSON.parse(json);
    }

    public static facultiesToJson(value: Faculties[]): string {
        return JSON.stringify(value);
    }
}
