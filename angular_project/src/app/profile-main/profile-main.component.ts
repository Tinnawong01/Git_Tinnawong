import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ActivatedRoute, Router } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClientModule, HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';
import { Convert as ProfileshowConverter, Profileshow } from '../model/profile.model';
import { AuthService } from '../service/AuthService.service';

@Component({
  selector: 'app-profile-main',
  standalone: true,
  imports: [RouterLink, CommonModule, HttpClientModule],
  providers: [DataServiceService],
  templateUrl: './profile-main.component.html',
  styleUrls: ['./profile-main.component.scss']
})
export class ProfileMainComponent implements OnInit {
  profileshowid: Profileshow[] = [];
  id_member: number = 0;

  constructor(
    private httpClient: HttpClient,
    private rou: ActivatedRoute,
    private router: Router,
    private dataService: DataServiceService,
    private authService: AuthService
  ) { }

  ngOnInit(): void {
    if (!this.authService.isAuthenticated()) {
      Swal.fire({
        icon: 'warning',
        title: 'กรุณาเข้าสู่ระบบ',
        text: 'คุณต้องเข้าสู่ระบบก่อนจึงจะสามารถใช้งานหน้านี้ได้'
      }).then(() => {
        this.router.navigate(['/login']);
      });
      return;
    }

    this.rou.queryParams.subscribe((Params) => {
      this.id_member = parseInt(Params['id_member']) || 0;
      if (this.id_member !== 0) {
        this.loadProfile();
      }
    });
  }

  loadProfile(): void {
    this.httpClient
      .get(`${this.dataService.apiEndpoint}/memberid/${this.id_member}`)
      .subscribe((data: any) => {
        this.profileshowid = [data]; 
      });
  }

  Memberid_password(id_member: number) {
    this.router.navigate(['/profile-password'], {
      queryParams: {
        id_member: id_member
      }
    });
  }

  Memberid_name(id_member: number) {
    this.router.navigate(['/profile-name'], {
      queryParams: {
        id_member: id_member
      }
    });
  }
}

