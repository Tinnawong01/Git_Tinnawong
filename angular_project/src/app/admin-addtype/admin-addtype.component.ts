import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';
import Swal from 'sweetalert2';
import { AuthService } from '../service/AuthService.service';
@Component({
  selector: 'app-admin-addtype',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './admin-addtype.component.html',
  styleUrls: ['./admin-addtype.component.scss']
})
export class AdminAddtypeComponent {
  selectedFile: File | null = null;
  imgBase64: string | null = null;
  imgstadium: File | null = null;

  constructor(
    private dataService: DataServiceService,
    private httpClient: HttpClient,
    private router: Router,
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
  }
  InsertStadium(stadium_name: string, location: string, info_stadium: string): void {
    const formData = new FormData();
    formData.append('stadium_name', stadium_name);
    formData.append('location', location);
    formData.append('info_stadium', info_stadium);
    formData.append('path_img', this.imgstadium!);
  
    const endpoint = `${this.dataService.apiEndpoint}/Insert_stadium`;
  
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
            this.router.navigate(['/admin-manage']);
          }
        });
      },
      (error) => {
        console.error('Failed to add stadium:', error);
        if (error.status === 400) {
          Swal.fire({
            title: 'เพิ่มข้อมูลไม่สำเร็จ!',
            text: 'ชื่อสนามกีฬามีอยู่แล้วในระบบ',
            icon: 'error',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#198b75',
          });
        } else {
          Swal.fire({
            title: 'เกิดข้อผิดพลาด!',
            text: 'ไม่สามารถเพิ่มข้อมูลได้ กรุณาลองอีกครั้ง',
            icon: 'error',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#198b75',
          });
        }
      }
    );
  }
  

  onFileSelected(event: any, fileKey: string): void {
    const file: File = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        if (fileKey === 'path_img') {
          this.imgstadium = file;
        }
      };
    }
  }
}
