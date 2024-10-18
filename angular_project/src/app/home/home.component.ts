import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ActivatedRoute, Router } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';
import { Convert as stadiumshowCvt, Stadiumshow } from '../model/stadiumshow.model';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-home',
  standalone: true,
  imports: [RouterLink, CommonModule, FormsModule],
  // ลบ providers: [DataServiceService] ออก
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss'],
})
export class HomeComponent implements OnInit {
  Stadiumshow: Stadiumshow[] = [];
  Idstadiums: number = 0;
  selectedStadiumId: number = 0;
  id_member: number = 0; 
  selectedDate: string = '';
  minDate: string = '';

  constructor(
    private dataService: DataServiceService, // ฉีด DataServiceService
    private httpClient: HttpClient,
    private router: Router,
    private rou: ActivatedRoute
  ) { }

  ngOnInit(): void {
    this.setMinDate();
    this.rou.queryParams.subscribe((Params) => {
      const userData = localStorage.getItem('userData');

      if (userData) {
        const user = JSON.parse(userData);
        this.Idstadiums = parseInt(Params['id_stadium']) || 0;
      }
      if (userData) {
        const user = JSON.parse(userData);
        this.id_member = user.id_member; 
      }

      this.httpClient
        .get(this.dataService.apiEndpoint + '/stadium_user')
        .subscribe((data: any) => {
          this.Stadiumshow = stadiumshowCvt.toStadiumshow(JSON.stringify(data));
          // console.log(this.Stadiumshow); // Removed this line
        });
    });
  }

  setMinDate(): void {
    const today = new Date();
    const year = today.getFullYear();
    const month = ('0' + (today.getMonth() + 1)).slice(-2);
    const day = ('0' + today.getDate()).slice(-2);
    this.minDate = `${year}-${month}-${day}`;
  }

  Stadiumshowid(id_stadium: number) {
    this.router.navigate(['/stadium-detail'], {
      queryParams: {
        id_stadium: id_stadium
      }
    });
  }
  
  

  Pleaselog() {
    if (this.isLoggedIn()) {
      this.router.navigate(['/booking-time']);
    } else {
      Swal.fire({
        icon: 'warning',
        title: 'กรุณาเข้าสู่ระบบ',
        text: 'กรุณาเข้าสู่ระบบก่อนทำการจองสนาม',
        confirmButtonText: 'ตกลง',
        allowOutsideClick: false,
        confirmButtonColor: '#198b75',
      }).then((result) => {
        if (result.isConfirmed) {
          this.router.navigate(['/login']);
        }
      });
    }
  }

  isLoggedIn(): boolean {
    return !!localStorage.getItem('userData');
  }

  searchBookingTime(): void {
    if (this.selectedStadiumId && this.selectedDate) {
      const userData = localStorage.getItem('userData');
      if (userData) {
        const user = JSON.parse(userData);
        this.router.navigate(['/booking-time'], {
          queryParams: {
            id_stadium: this.selectedStadiumId,
            date: this.selectedDate,
            id_member: this.id_member 
          }
        });
      } else {
        Swal.fire({
          icon: 'warning',
          title: 'กรุณาเข้าสู่ระบบ',
          text: 'กรุณาเข้าสู่ระบบก่อนทำการจองสนาม',
          confirmButtonText: 'ตกลง',
          allowOutsideClick: false,
          confirmButtonColor: '#198b75',
        }).then((result) => {
          if (result.isConfirmed) {
            this.router.navigate(['/login']);
          }
        });
      }
    } else {
      Swal.fire({
        icon: 'warning',
        title: 'กรุณาเลือกสนามกีฬาและวันที่',
        text: 'กรุณาเลือกสนามกีฬาและวันที่ก่อนทำการค้นหา',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75'
      });
    }
  }

  searchBookingStadiums(): void {
    // ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
    const userData = localStorage.getItem('userData');
    if (userData) {
      const user = JSON.parse(userData);
      this.router.navigate(['/booking-time'], {
        queryParams: {
          id_stadium: this.selectedStadiumId, 
          id_member: this.id_member 
        }
      });
    } else {
      Swal.fire({
        icon: 'warning',
        title: 'กรุณาเข้าสู่ระบบ',
        text: 'กรุณาเข้าสู่ระบบก่อนทำการจองสนาม',
        confirmButtonText: 'ตกลง',
        allowOutsideClick: false,
        confirmButtonColor: '#198b75',
      }).then((result) => {
        if (result.isConfirmed) {
          this.router.navigate(['/login']);
        }
      });
    }
  }

  searchBookingStadium(id_stadium: number): void {
    const userData = localStorage.getItem('userData');
    if (userData) {
      const user = JSON.parse(userData);
      this.router.navigate(['/booking-time'], {
        queryParams: {
          id_stadium: id_stadium,
          id_member: this.id_member
        }
      });
    } else {
      Swal.fire({
        icon: 'warning',
        title: 'กรุณาเข้าสู่ระบบ',
        text: 'กรุณาเข้าสู่ระบบก่อนทำการจองสนาม',
        confirmButtonText: 'ตกลง',
        allowOutsideClick: false,
        confirmButtonColor: '#198b75',
      }).then((result) => {
        if (result.isConfirmed) {
          this.router.navigate(['/login']);
        }
      });
    }
  }
}
