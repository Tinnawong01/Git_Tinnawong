export interface Stadiumid {
  id_number: number;
  number_name: string;
  stadium_name: string;
  id_stadium: number;
}

export class Convert {
  public static toStadiumid(json: string): Stadiumid[] {
    return JSON.parse(json);
  }

  public static stadiumidToJson(value: Stadiumid[]): string {
    return JSON.stringify(value);
  }
}
