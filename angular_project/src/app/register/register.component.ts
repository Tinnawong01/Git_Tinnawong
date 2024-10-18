import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { HttpClientModule } from '@angular/common/http';
import { DataServiceService } from '../service/data.service.service';
import { FormsModule } from '@angular/forms';
import { Convert as FacultiesConverter, Faculties } from '../model/faculties.model';
import Swal from 'sweetalert2';
@Component({
  selector: 'app-register',
  standalone: true,
  imports: [HttpClientModule, FormsModule, CommonModule],
  providers: [DataServiceService],
  templateUrl: './register.component.html',
  styleUrl: './register.component.scss'
})
export class RegisterComponent implements OnInit {
  faculties: any[] = [];
  departments: any[] = [];
  selectedFacultyId: number = 0;
  showPassword: boolean = false;
  showConfirmPassword: boolean = false;
  constructor(
    private dataService: DataServiceService,
    private httpClient: HttpClient,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.getFaculties();
  }

  getFaculties() {
    this.httpClient.get<any[]>(`${this.dataService.apiEndpoint}/faculties`).subscribe(
      (response: any[]) => {
        this.faculties = response;
      },
      (error) => {
        console.error('Error loading faculties:', error);
      }
    );
  }

  getDepartments(facultyId: number) {
    this.selectedFacultyId = facultyId;
    if (this.selectedFacultyId !== 0) {
      this.httpClient.get<any[]>(`${this.dataService.apiEndpoint}/departments/${this.selectedFacultyId}`).subscribe(
        (response: any[]) => {
          this.departments = response;
        },
        (error) => {
          console.error('Error loading departments:', error);
        }
      );
    }
  }

  register(
    email: any, password: any, confirmPassword: any, std_id: any,
    prefix: any, fname: any, lname: any, sex: any, faculty_id: any, department: any
  ) {
    const emailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
    const studentIdPattern = /^\d{10}$/;  // ตรวจสอบรหัสนิสิตให้เป็นตัวเลข 10 หลัก
    const passwordPattern = /^(?=.*[A-Za-z0-9])[A-Za-z0-9]{8,}$/; // รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร (ภาษาอังกฤษหรือตัวเลข)
  
    // ตรวจสอบว่าทุกช่องข้อมูลไม่ว่างเปล่า
    if (!std_id || !email || !password || !confirmPassword || !prefix || !fname || !lname || !sex || faculty_id === '0' || department === '0') {
      Swal.fire({
        title: 'ข้อมูลไม่ครบถ้วน',
        text: 'กรุณากรอกข้อมูลทุกช่องให้ครบถ้วน',
        icon: 'error',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75',
      });
      return;
    }
  
    if (!studentIdPattern.test(std_id)) {
      Swal.fire({
        title: 'รหัสนิสิตไม่ถูกต้อง',
        text: 'กรุณากรอกรหัสนิสิตเป็นตัวเลข 10 หลักเท่านั้น',
        icon: 'error',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75',
      });
      return;
    }
  
    if (!emailPattern.test(email)) {
      Swal.fire({
        title: 'อีเมลไม่ถูกต้อง',
        text: 'กรุณากรอกอีเมลที่ถูกต้อง',
        icon: 'error',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75',
      });
      return;
    }
  
    if (!passwordPattern.test(password)) {
      Swal.fire({
        title: 'รหัสผ่านไม่ถูกต้อง',
        text: 'กรุณากรอกรหัสผ่านให้มีความยาว 8 ตัวอักษรขึ้นไป และไม่ใช้ตัวอักษรภาษาไทย',
        icon: 'error',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75',
      });
      return;
    }
  
    if (password !== confirmPassword) {
      Swal.fire({
        title: 'รหัสผ่านไม่ตรงกัน',
        text: 'กรุณากรอกรหัสผ่านให้ตรงกัน',
        icon: 'error',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75',
      });
      return;
    }
  
    // ส่งข้อมูลไปยัง API
    const registerData = { 
      email, password, std_id, prefix, fname, lname, sex, faculty_id, department 
    };
  
    this.httpClient.post(this.dataService.apiEndpoint + '/register', registerData).subscribe(
      (response: any) => {
        Swal.fire({
          title: 'สมัครสมาชิกสำเร็จ!',
          text: 'คุณได้ทำการสมัครสมาชิกเรียบร้อยแล้ว',
          icon: 'success',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        }).then((result) => {
          if (result.isConfirmed) {
            this.router.navigate(['/login']);
          }
        });
      },
      (error) => {
        Swal.fire({
          title: 'เกิดข้อผิดพลาด!',
          text: 'ไม่สามารถสมัครสมาชิกได้ โปรดลองใหม่อีกครั้ง',
          icon: 'error',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        });
        console.error('Error:', error);
      }
    );
  }
  
  toggleShowPassword(): void {
      this.showPassword = !this.showPassword;
  }
  
  toggleShowConfirmPassword(): void {
      this.showConfirmPassword = !this.showConfirmPassword;
  }
  
}