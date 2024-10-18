import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { DataServiceService } from '../service/data.service.service';
import { Convert as stadiumshowCvt, Stadiumshow } from '../model/stadiumshow.model';
import { RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-stadium-detail',
  templateUrl: './stadium-detail.component.html',
  styleUrls: ['./stadium-detail.component.scss'],
  standalone: true,
  imports: [RouterLink, CommonModule],
  providers: [DataServiceService],
})
export class StadiumDetailComponent implements OnInit {
  Stadiumshow: Stadiumshow[] = [];
  Idstadiums: number = 0;

  constructor(
    private dataService: DataServiceService,
    private httpClient: HttpClient,
    private router: Router,
    private rou: ActivatedRoute
  ) {}

  ngOnInit(): void {
    this.rou.queryParams.subscribe((Params) => {
      this.Idstadiums = parseInt(Params['id_stadium']) || 0;
      if (this.Idstadiums !== 0) {
        this.loadidstadiumid(); 
      }
    });
  }
  
  loadidstadiumid(): void {
    this.httpClient
      .get(`${this.dataService.apiEndpoint}/stadium_user/${this.Idstadiums}`)
      .subscribe((data: any) => {
        this.Stadiumshow = stadiumshowCvt.toStadiumshow(JSON.stringify(data));
      });
  }
  
}
