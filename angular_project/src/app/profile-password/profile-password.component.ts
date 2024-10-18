import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ActivatedRoute, Router } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';
import { Convert as ProfileshowConverter, Profileshow } from '../model/profile.model';
import { AuthService } from '../service/AuthService.service';
@Component({
  selector: 'app-profile-password',
  standalone: true,
  imports: [RouterLink, CommonModule],
  providers: [DataServiceService],
  templateUrl: './profile-password.component.html',
  styleUrl: './profile-password.component.scss'
})
export class ProfilePasswordComponent implements OnInit {
  profileshowid: Profileshow[] = [];
  id_member: number = 0;
  passwordVisible: boolean = false;
  confirmPasswordVisible: boolean = false;

  constructor(
    private httpClient: HttpClient,
    private route: ActivatedRoute,
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
    
    this.route.queryParams.subscribe((Params) => {
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

  confirmEditPassword(password: string, confirmPassword: string): void {
    const thaiRegex = /[ก-๙]/; // ตรวจสอบตัวอักษรภาษาไทย
  
    if (!password || !confirmPassword) {
      Swal.fire({
        title: 'กรุณากรอกรหัสผ่าน!',
        text: 'ทั้งรหัสผ่านใหม่และยืนยันรหัสผ่านต้องถูกกรอก',
        icon: 'error',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75',
      });
      return;
    }
  
    if (password.length < 8) {
      Swal.fire({
        title: 'รหัสผ่านสั้นเกินไป!',
        text: 'กรุณากรอกรหัสผ่านอย่างน้อย 8 ตัวอักษร',
        icon: 'error',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75',
      });
      return;
    }
  
    if (thaiRegex.test(password)) {
      Swal.fire({
        title: 'รหัสผ่านต้องไม่มีอักษรภาษาไทย!',
        text: 'กรุณากรอกรหัสผ่านโดยไม่มีตัวอักษรภาษาไทย',
        icon: 'error',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75',
      });
      return;
    }
  
    if (password !== confirmPassword) {
      Swal.fire({
        title: 'รหัสผ่านไม่ตรงกัน!',
        text: 'กรุณากรอกรหัสผ่านให้ตรงกัน',
        icon: 'error',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75',
      });
      return;
    }
  
    Swal.fire({
      title: 'ยืนยันการเปลี่ยนแปลงรหัสผ่าน',
      text: 'คุณต้องการเปลี่ยนแปลงรหัสผ่านหรือไม่?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'ยืนยัน',
      cancelButtonText: 'ยกเลิก',
      confirmButtonColor: '#198b75',
      cancelButtonColor: '#d33',
    }).then((result) => {
      if (result.isConfirmed) {
        this.EditPassword(password);
      }
    });
  }
  

  EditPassword(password: string): void {
    const email = this.profileshowid?.[0]?.email;
    if (!email) {
      Swal.fire({
        title: 'ไม่พบอีเมล!',
        text: 'กรุณาตรวจสอบข้อมูลอีเมล',
        icon: 'error',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75',
      });
      return;
    }

    const formData = new FormData();
    formData.append('id_member', this.id_member.toString());
    formData.append('email', email);
    formData.append('password', password);

    const endpoint = `${this.dataService.apiEndpoint}/Edit_password`;

    this.httpClient.post(endpoint, formData).subscribe(
      (response: any) => {
        console.log('Password updated successfully:', response);
        Swal.fire({
          title: 'เปลี่ยนรหัสผ่านสำเร็จ!',
          text: 'คุณได้ทำการเปลี่ยนรหัสผ่านสำเร็จ',
          icon: 'success',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        }).then(() => {
          this.router.navigate(['/profile-main'], { queryParams: { id_member: this.id_member } });
          location.reload();
        });
      },
      (error) => {
        console.error('Failed to update password:', error);
        Swal.fire({
          title: 'เปลี่ยนรหัสผ่านไม่สำเร็จ!',
          text: 'เกิดข้อผิดพลาดในการเปลี่ยนรหัสผ่าน',
          icon: 'error',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        });
      }
    );
  }

  togglePasswordVisibility(): void {
    this.passwordVisible = !this.passwordVisible;
    const passwordField = document.getElementById('password') as HTMLInputElement;
    if (passwordField) {
      passwordField.type = this.passwordVisible ? 'text' : 'password';
    }
  }

  toggleConfirmPasswordVisibility(): void {
    this.confirmPasswordVisible = !this.confirmPasswordVisible;
    const confirmPasswordField = document.getElementById('confirmPassword') as HTMLInputElement;
    if (confirmPasswordField) {
      confirmPasswordField.type = this.confirmPasswordVisible ? 'text' : 'password';
    }
  }
}
