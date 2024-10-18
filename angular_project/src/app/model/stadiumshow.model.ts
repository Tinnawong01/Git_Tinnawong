export interface Stadiumshow {
    id_stadium:   number;
    stadium_name: string;
    location:     string;
    info_stadium: string;
    path_img:     string;
}

export class Convert {
    public static toStadiumshow(json: string): Stadiumshow[] {
        return JSON.parse(json);
    }

    public static stadiumshowToJson(value: Stadiumshow[]): string {
        return JSON.stringify(value);
    }
}
