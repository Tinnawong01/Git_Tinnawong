import { Component } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Router, ActivatedRoute } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import Swal from 'sweetalert2';
import { RouterLink } from '@angular/router';
import { Convert as stadiumCvt, Stadium } from '../model/stadium.model';
import { AuthService } from '../service/AuthService.service';
@Component({
  selector: 'app-admin-edittype',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './admin-edittype.component.html',
  styleUrls: ['./admin-edittype.component.scss']
})
export class AdminEdittypeComponent {
  id_stadium: number = 0;
  Idstadiums: number = 0;
  Stadiums: Stadium[] = [];
  selectedFile: File | null = null;
  imgBase64: string | null = null;
  imgstadium: File | null = null;

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
        this.id_stadium = parseInt(Params['id_stadium']) || 0;
      }

      this.httpClient
        .get(this.dataService.apiEndpoint + '/stadiums/' + this.id_stadium)
        .subscribe((data: any) => {
          this.Stadiums = stadiumCvt.toStadium(JSON.stringify(data));
        }, (error: HttpErrorResponse) => {
          console.error('Failed to load stadium data:', error);
          Swal.fire({
            title: 'โหลดข้อมูลไม่สำเร็จ!',
            text: 'ไม่สามารถโหลดข้อมูลสนามได้',
            icon: 'error',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#198b75',
          });
        });
    });
  }
  InsertStadium(stadium_name: string, location: string, info_stadium: string): void {
    if (!this.id_stadium) {
        Swal.fire({
            title: 'ไม่พบ ID สนาม!',
            text: 'กรุณาตรวจสอบข้อมูล ID สนาม',
            icon: 'error',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#198b75',
        });
        return;
    }

    const formData = new FormData();
    formData.append('stadium_name', stadium_name);
    formData.append('location', location);
    formData.append('info_stadium', info_stadium);
    if (this.imgstadium) {
        formData.append('path_img', this.imgstadium);
    }
    formData.append('id_stadium', this.id_stadium.toString());
    const endpoint = `${this.dataService.apiEndpoint}/Edit_stadium`;

    this.httpClient.post(endpoint, formData).subscribe(
        (response: any) => {
            console.log('Stadium updated successfully:', response);
            Swal.fire({
                title: 'แก้ไขข้อมูลสำเร็จ!',
                text: 'คุณได้ทำการแก้ไขข้อมูลสำเร็จ',
                icon: 'success',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#198b75',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.router.navigate(['/admin-manage']);
                }
            });
        },
        (error: HttpErrorResponse) => {
            console.error('Failed to update stadium:', error);
            let errorMessage = 'คุณได้ทำการแก้ไขข้อมูลไม่สำเร็จ';
            if (error.error && error.error.message) {
                errorMessage = error.error.message; // แสดงข้อความข้อผิดพลาดที่ได้จาก API
            }
            Swal.fire({
                title: 'แก้ไขข้อมูลไม่สำเร็จ!',
                text: errorMessage,
                icon: 'error',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#198b75',
            });
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
