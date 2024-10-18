import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ActivatedRoute, Router } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';
import { Convert as stadiumCvt, Stadium } from '../model/stadium.model';
import { AuthService } from '../service/AuthService.service';

@Component({
  selector: 'app-admin-manage',
  standalone: true,
  imports: [RouterLink, CommonModule],
  providers: [DataServiceService],
  templateUrl: './admin-manage.component.html',
  styleUrls: ['./admin-manage.component.scss'],
})
export class AdminManageComponent implements OnInit {
  Idstadiums: number = 0;
  IdMember: number = 0;

  constructor(
    private dataService: DataServiceService,
    private httpClient: HttpClient,
    private router: Router,
    private rou: ActivatedRoute,
    private authService: AuthService
  ) { }

  Stadiums: Stadium[] = [];

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

    const userData = localStorage.getItem('userData');
    if (userData) {
      const user = JSON.parse(userData);
      this.IdMember = user.id_member;
    }

    this.rou.queryParams.subscribe((Params) => {
      this.Idstadiums = parseInt(Params['id_stadium']) || 0;
      this.httpClient.get(this.dataService.apiEndpoint + '/stadium')
        .subscribe((data: any) => {
          this.Stadiums = stadiumCvt.toStadium(JSON.stringify(data));
        });
    });
  }

  Stadiumid(id_stadium: number) {
    this.router.navigate(['/admin-allnumber'], {
      queryParams: {
        id_stadium: id_stadium
      }
    });
  }

  Editstadium(id_stadium: number) {
    this.router.navigate(['/admin-edittype'], {
      queryParams: {
        id_stadium: id_stadium
      }
    });
  }

  delete(id_stadium: number) {
    this.router.navigate(['/admin-manage'], {
      queryParams: {
        id_stadium: id_stadium
      }
    });
  }

  confirmDelete(id_stadium: number): void {
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
        this.deleteStadium(id_stadium);
      }
    });
  }

  Memberid(id_member: number) {
    this.router.navigate(['/admin-alltime'], {
      queryParams: {
        id_member: id_member // ใช้พารามิเตอร์ id_member ที่รับเข้ามา
      }
    });
  }

  deleteStadium(id_stadium: number): void {
    const apiUrl = `${this.dataService.apiEndpoint}/delete_stadium/${id_stadium}`;
    this.httpClient.delete(apiUrl).subscribe(
      (response: any) => {
        console.log('Stadium deleted successfully:', response);
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
        console.error('Failed to delete stadium:', error);
        Swal.fire({
          title: 'ลบข้อมูลไม่สำเร็จ!',
          text: 'สนามได้มีการใช้งานไปเเล้ว',
          icon: 'error',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        });
      }
    );
  }
}
