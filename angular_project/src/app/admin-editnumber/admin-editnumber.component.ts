import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import Swal from 'sweetalert2';
import { DataServiceService } from '../service/data.service.service';
import { RouterLink } from '@angular/router';

import { Convert as stadiumidCvt, Stadiumid } from '../model/stadiumid.model';
import { AuthService } from '../service/AuthService.service';
@Component({
  selector: 'app-admin-editnumber',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './admin-editnumber.component.html',
  styleUrls: ['./admin-editnumber.component.scss']
})
export class AdminEditnumberComponent implements OnInit {
  IdNumber: number = 0; 
  Stadiumids: Stadiumid[] = [];
  selectedFile: File | null = null;
  idStadium: number | undefined; 

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
        this.IdNumber = parseInt(Params['id_number']) || 0;
        if (this.IdNumber !== 0) {
          this.loadIdNumber(); 
        }
      }
    });
  }
  loadIdNumber(): void {
    this.httpClient
      .get(this.dataService.apiEndpoint + '/stadium_number/' + this.IdNumber)
      .subscribe((data: any) => {
        const jsonString = JSON.stringify(data);
        this.Stadiumids = stadiumidCvt.toStadiumid(jsonString); 
        if (this.Stadiumids.length > 0) {
          this.idStadium = this.Stadiumids[0].id_stadium;
        }
      });
  }

  onIdStadiumChange(id_stadium: number): void {
    this.idStadium = id_stadium; 
  }

  EditNumber(id_stadium: string, number_name: string): void {
    if (!id_stadium) {
      Swal.fire({
        title: 'ไม่พบ ID สนาม!',
        text: 'กรุณาตรวจสอบข้อมูล ID สนาม',
        icon: 'error',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75',
      });
      return;
    }
    if (!this.IdNumber) {
      Swal.fire({
        title: 'ไม่พบ ID หมายเลข!',
        text: 'กรุณาตรวจสอบข้อมูล ID หมายเลข',
        icon: 'error',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75',
      });
      return;
    }
  
    const formData = new FormData();
    formData.append('id_stadium', id_stadium);
    formData.append('number_name', number_name);
    formData.append('id_number', this.IdNumber.toString());
  
    const endpoint = `${this.dataService.apiEndpoint}/Edit_number`;
  
    this.httpClient.post(endpoint, formData).subscribe(
      (response: any) => {
        Swal.fire({
          title: 'อัปเดตข้อมูลสำเร็จ!',
          text: 'คุณได้ทำการอัปเดตข้อมูลสำเร็จ',
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
        if (error.status === 400 && error.error.message === 'Duplicate number name detected') {
          Swal.fire({
            title: 'ชื่อหมายเลขซ้ำ!',
            text: 'ชื่อหมายเลขนี้มีอยู่แล้วในระบบ กรุณาใช้ชื่ออื่น',
            icon: 'error',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#198b75',
          });
        } else {
          Swal.fire({
            title: 'อัปเดตข้อมูลไม่สำเร็จ!',
            text: 'คุณได้ทำการอัปเดตข้อมูลไม่สำเร็จ',
            icon: 'error',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#198b75',
          });
        }
      }
    );
  }
  

}
