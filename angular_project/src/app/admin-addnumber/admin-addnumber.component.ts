import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import Swal from 'sweetalert2';
import { DataServiceService } from '../service/data.service.service';
import { Convert as stadiumCvt, Stadium } from '../model/stadium.model';
import { Convert as stadiumidCvt, Stadiumid } from '../model/stadiumid.model';
import { RouterLink } from '@angular/router';
import { AuthService } from '../service/AuthService.service';
@Component({
  selector: 'app-admin-addnumber',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './admin-addnumber.component.html',
  styleUrls: ['./admin-addnumber.component.scss']
})
export class AdminAddnumberComponent implements OnInit {
  Idstadiums: number = 0;
  Stadiums: Stadium[] = [];
  Stadiumids: Stadiumid[] = [];

  constructor(
    private dataService: DataServiceService,
    private httpClient: HttpClient,
    private router: Router,
    private rou: ActivatedRoute,
    private authService: AuthService
  ) { }

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
        this.Idstadiums = parseInt(Params['id_stadium']) || 0;

      }

      this.httpClient
        .get(this.dataService.apiEndpoint + '/stadiums/' + this.Idstadiums)
        .subscribe((data: any) => {
          this.Stadiums = stadiumCvt.toStadium(JSON.stringify(data));
        });
    });
  }

  InsertNumber(id_stadium: string, number_name: string): void {
    const formData = new FormData();
    formData.append('id_stadium', id_stadium);
    formData.append('number_name', number_name);
  
    const endpoint = `${this.dataService.apiEndpoint}/Insert_number`;
  
    this.httpClient.post(endpoint, formData).subscribe(
      (response: any) => {
        console.log('Stadium added successfully:', response);
        Swal.fire({
          title: 'เพิ่มข้อมูลสำเร็จ!',
          text: 'คุณได้ทำการเพิ่มข้อมูลสำเร็จ',
          icon: 'success',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        }).then((result) => {
          if (result.isConfirmed) {
            this.router.navigate(['/admin-allnumber'], { queryParams: { id_stadium } });
          }
        });
      },
      (error) => {
        if (error.status === 409) {
          Swal.fire({
            title: 'หมายเลขซ้ำ!',
            text: 'หมายเลขนี้มีอยู่ในสนามแล้ว กรุณากรอกหมายเลขอื่น',
            icon: 'error',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#198b75',
          });
        } else {
          console.error('Failed to add stadium:', error);
          Swal.fire({
            title: 'เพิ่มข้อมูลไม่สำเร็จ!',
            text: 'คุณได้ทำการเพิ่มข้อมูลไม่สำเร็จ',
            icon: 'error',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#198b75',
          });
        }
      }
    );
  }
  
}
