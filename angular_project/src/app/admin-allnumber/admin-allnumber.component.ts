import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ActivatedRoute, Router } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';

import { Convert as stadiumCvt, Stadium } from '../model/stadium.model';
import { Convert as stadiumidCvt, Stadiumid } from '../model/stadiumid.model';
import { AuthService } from '../service/AuthService.service';
@Component({
  selector: 'app-admin-allnumber',
  standalone: true,
  imports: [RouterLink, CommonModule],
  providers: [DataServiceService],
  templateUrl: './admin-allnumber.component.html',
  styleUrls: ['./admin-allnumber.component.scss']
})
export class AdminAllnumberComponent implements OnInit {
  constructor(
    private dataService: DataServiceService,
    private httpClient: HttpClient,
    private router: Router,
    private rou: ActivatedRoute,
    private authService: AuthService
  ) { }

  Idstadiums: number = 0;
  Stadiums: Stadium[] = [];
  Stadiumids: Stadiumid[] = []; 
  id_member: number = 0; 

  ngOnInit(): void {
    if (!this.authService.isAdmin()) {
      Swal.fire({
        icon: 'error',
        title: 'ไม่มีสิทธิ์เข้าถึง',
        text: 'คุณต้องเป็นผู้ดูแลระบบเพื่อเข้าถึงหน้านี้'
      }).then(() => {
        this.router.navigate(['/']); 
      });
      return;
    }
    this.rou.queryParams.subscribe((Params) => {
      const userData = localStorage.getItem('userData');

      if (userData) {
        const user = JSON.parse(userData);
        this.id_member = user.id_member; 
        this.Idstadiums = parseInt(Params['id_stadium']) || 0;
        if (this.Idstadiums !== 0) {
          this.loadidstadium();
        }
      }
      this.httpClient
        .get(this.dataService.apiEndpoint + '/stadium')
        .subscribe((data: any) => {
          this.Stadiums = stadiumCvt.toStadium(JSON.stringify(data));
        });
    });
  }

  Numberid(id_stadium: number) {
    this.router.navigate(['/admin-addnumber'], {
      queryParams: {
        id_stadium: id_stadium
      }
    });
  }

  Editnumber(id_stadium: number, id_number: number) {
    this.router.navigate(['/admin-editnumber'], { queryParams: { id_stadium: id_stadium, id_number: id_number } });
  }

  searchBookingTime(id_stadium: number, id_number: number, id_member: number) {
    this.router.navigate(['/admin-edittime'], { queryParams: { id_stadium: id_stadium, id_number: id_number, id_member: id_member } });
  }

  loadidstadium(): void {
    this.httpClient
      .get(this.dataService.apiEndpoint + '/stadium/' + this.Idstadiums)
      .subscribe((data: any) => {
        const jsonString = JSON.stringify(data);
        this.Stadiumids = stadiumidCvt.toStadiumid(jsonString); 
      });
  }

  getFilteredStadiums(): Stadium[] {
    return this.Stadiums.filter(stadium => stadium.id_stadium === this.Idstadiums);
  }

  delete(id_number: number) {
    this.router.navigate(['/admin-manage'], {
      queryParams: {
        id_number: id_number
      }
    });
  }

  confirmDelete(id_number: number): void {
    Swal.fire({
      title: 'ยืนยันการลบ?',
      text: 'คุณแน่ใจหรือว่าต้องการลบสนามนี้?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#198b75',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ใช่, ลบเลย!',
      cancelButtonText: 'ยกเลิก'
    }).then((result) => {
      if (result.isConfirmed) {
        this.deleteNumber(id_number);
      }
    });
  }

  deleteNumber(id_number: number): void {
    const apiUrl = `${this.dataService.apiEndpoint}/delete_number/${id_number}`;
    this.httpClient.delete(apiUrl).subscribe(
      (response: any) => {
        console.log('Stadium number deleted successfully:', response);
        Swal.fire({
          title: 'ลบข้อมูลสำเร็จ!',
          text: 'คุณได้ทำการลบข้อมูลสำเร็จ',
          icon: 'success',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        }).then((result) => {
          if (result.isConfirmed) {
            location.reload(); 
          }
        });
      },
      (error) => {
        console.error('Failed to delete stadium number:', error);
        Swal.fire({
          title: 'ลบข้อมูลไม่สำเร็จ!',
          text: 'คุณได้ทำการลบข้อมูลไม่สำเร็จ',
          icon: 'error',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        });
      }
    );
  }
}
