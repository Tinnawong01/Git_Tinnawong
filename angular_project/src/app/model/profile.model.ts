export interface Profileshow {
    id_member: number;
    email:     string;
    password:  string;
    prefix:    string;
    fname:     string;
    lname:     string;
    role:      string; 
}

export class Convert {
    public static toProfileshow(json: string): Profileshow[] {
        return JSON.parse(json);
    }

    public static profileshowToJson(value: Profileshow[]): string {
        return JSON.stringify(value);
    }
}
