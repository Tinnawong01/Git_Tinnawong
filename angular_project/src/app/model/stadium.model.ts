export interface Stadium {
    id_stadium:   number;
    stadium_name: string;
    location:     string;
    info_stadium: string;
    id_number : number;
    
}

export class Convert {
    public static toStadium(json: string): Stadium[] {
        return JSON.parse(json);
    }

    public static stadiumToJson(value: Stadium[]): string {
        return JSON.stringify(value);
    }
}
