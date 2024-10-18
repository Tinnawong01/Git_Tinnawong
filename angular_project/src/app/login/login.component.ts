import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClientModule } from '@angular/common/http';
import { DataServiceService } from '../service/data.service.service';
import { FormsModule } from '@angular/forms';
import Swal from 'sweetalert2';
import { RouterLink } from '@angular/router';
@Component({
  selector: 'app-login',
  standalone: true,
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss'],
  imports: [HttpClientModule, FormsModule,RouterLink], 
  providers: [DataServiceService], 
})
export class LoginComponent {
  constructor(
    private dataService: DataServiceService,
    private httpClient: HttpClient,
    private router: Router
  ) { }

  login(email: string, password: string) {
    const loginData = { email: email, password: password };

    this.httpClient.post(this.dataService.apiEndpoint + '/login', loginData).subscribe(
      (response: any) => {
        console.log('Login successful:', response);
        localStorage.setItem('isLoggedIn', 'true');
        localStorage.setItem('userData', JSON.stringify(response.user));

        Swal.fire({
          title: 'เข้าสู่ระบบสำเร็จ!',
          text: 'คุณได้ทำการเข้าสู่ระบบสำเร็จ',
          icon: 'success',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
          
        }).then(() => {
          const memberId = response.user?.member_id;
          const role = response.user?.role;
        if (memberId) {
            localStorage.setItem('memberId', memberId.toString());
          }

          if (role === 'admin') {
            this.router.navigate(['/admin-main']).then(() => {
              window.location.reload();
            });
          } else {
            this.router.navigate(['/'], { queryParams: { member_id: memberId } }).then(() => {
              window.location.reload();
            });
          }
        });
      },
      (error: any) => {
        console.error('Login failed:', error);
        Swal.fire({
          title: 'การเข้าสู่ระบบไม่สำเร็จ',
          text: 'กรุณาตรวจสอบอีเมลหรือรหัสผ่านของคุณอีกครั้ง',
          icon: 'error',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        });
      }
    );
  }
}
