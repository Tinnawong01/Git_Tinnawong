import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ActivatedRoute, Router } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';
import { AuthService } from '../service/AuthService.service';
import { Location } from '@angular/common';

@Component({
  selector: 'app-booking-confirm',
  standalone: true,
  imports: [RouterLink, CommonModule],
  providers: [DataServiceService],
  templateUrl: './booking-confirm.component.html',
  styleUrl: './booking-confirm.component.scss'
})
export class BookingConfirmComponent implements OnInit {
  stadiumType: string = '';
  stadiumInfo: string = '';
  selectedDate: string = '';
  selectedTime: string = '';
  id_stadium: string = '';
  id_number: string = '';
  booking_date: string = '';
  id_time: string = '';
  id_member: string = '';
  present_date: string = '';
  present_time: string = '';
  
  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private dataService: DataServiceService,
    private httpClient: HttpClient,
    private authService: AuthService,
    private location: Location
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
  
    this.route.queryParams.subscribe(params => {
      this.id_stadium = params['id_stadium'];
      this.id_number = params['id_number'];
      this.booking_date = params['booking_date'];
      this.id_time = params['id_time'];
      this.id_member = params['id_member'];
      this.present_date = params['present_date'];  // ดึง present_date จาก URL
      this.present_time = params['present_time'];  // ดึง present_time จาก URL
  
      this.fetchStadiumDetails(this.id_stadium, this.id_number);
    });
  }
  

  fetchStadiumDetails(id_stadium: string, id_number: string): void {
    this.httpClient.get(`${this.dataService.apiEndpoint}/stadium/${id_stadium}`)
      .subscribe((data: any) => {
        const stadiumDetails = data.find((stadium: any) => stadium.id_number == id_number);
        if (stadiumDetails) {
          this.stadiumType = stadiumDetails.stadium_name;
          this.stadiumInfo = stadiumDetails.number_name;
        }
      });
  }

  Userbooking(booking_date: string, id_stadium: string, id_number: string, id_time: string, id_member: string): void {
    // ตรวจสอบว่าการจองทำภายใน 2 ชั่วโมงจากเวลาปัจจุบันหรือไม่
    const bookingDateTime = new Date(`${booking_date}T${this.getFormattedTime(id_time).split(' - ')[0]}`);
    const currentDateTime = new Date(`${this.present_date}T${this.present_time}`);
    const twoHoursInMilliseconds = 2 * 60 * 60 * 1000; // 2 ชั่วโมงเป็นมิลลิวินาที
  
    if (bookingDateTime.getTime() - currentDateTime.getTime() < twoHoursInMilliseconds) {
      Swal.fire({
        title: 'ไม่สามารถทำการจองได้!',
        text: 'คุณไม่สามารถทำการจองภายใน 2 ชั่วโมงจากเวลาปัจจุบัน',
        icon: 'error',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75',
      });
      return; // หยุดการทำงานของฟังก์ชัน
    }
  
    const formData = new FormData();
    formData.append('booking_date', booking_date);
    formData.append('id_stadium', id_stadium);
    formData.append('id_number', id_number);
    formData.append('id_time', id_time);
    formData.append('id_member', id_member);
    formData.append('present_date', this.present_date);
    formData.append('present_time', this.present_time);
  
    const endpoint = `${this.dataService.apiEndpoint}/user_booking`;
  
    this.httpClient.post(endpoint, formData).subscribe(
      (response: any) => {
        console.log('Stadium booked successfully:', response);
        Swal.fire({
          title: 'คุณได้จองสนามกีฬาเสร็จสิ้น',
          icon: 'success',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        }).then((result) => {
          if (result.isConfirmed) {
            this.router.navigate(['/qr-code'], { queryParams: { id_member: id_member } });
          }
        });
      },
      (error) => {
        console.error('Failed to book stadium:', error);
        if (error.error.message.includes('มีการจองแล้วในช่วงเวลาดังกล่าว')) {
          Swal.fire({
            title: 'การจองไม่สำเร็จ!',
            text: 'สนามนี้ถูกจองโดยผู้ใช้งานคนอื่นในช่วงเวลาดังกล่าว กรุณาเลือกเวลาอื่น.',
            icon: 'error',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#198b75',
          });
        } else {
          Swal.fire({
            title: 'ไม่สามารถจองสนามในวันที่เลือกได้',
            text: 'เนื่องจากมีการจองสนามในวันเดียวกัน',          
            icon: 'error',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#198b75',
          });
        }
      }
    );
  }
  
  cancelBooking(): void {
    Swal.fire({
      icon: 'question',
      title: 'คุณต้องการยกเลิกการจองนี้หรือไม่?',     
      showCancelButton: true,
      confirmButtonText: 'ใช่, ยกเลิกการจอง',
      cancelButtonText: 'ไม่',
      confirmButtonColor: '#198b75',
      cancelButtonColor: '#3085d6'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire('ยกเลิกการจองเรียบร้อย!', '', 'success').then(() => {
          // กลับไปหน้าที่มาก่อนหน้านี้
          this.location.back();
        });
      }
    });
    
    
  }


  formattedDate(date: string): string {
    const options: Intl.DateTimeFormatOptions = { day: 'numeric', month: 'long', year: 'numeric' };
    return new Date(date).toLocaleDateString('th-TH', options);
  }
  getFormattedTime(id_time: string): string {
    let timeSlot: string = '';

    switch (id_time) {
      case '1':
        timeSlot = '08:00 - 09:00';
        break;
      case '2':
        timeSlot = '09:00 - 10:00';
        break;
      case '3':
        timeSlot = '10:00 - 11:00';
        break;
      case '4':
        timeSlot = '11:00 - 12:00';
        break;
      case '5':
        timeSlot = '12:00 - 13:00';
        break;
      case '6':
        timeSlot = '13:00 - 14:00';
        break;
      case '7':
        timeSlot = '14:00 - 15:00';
        break;
        case '8':
          timeSlot = '15:00 - 16:00';
          break;
      default:
        timeSlot = 'เวลาไม่ระบุ';
        break;
    }

    return timeSlot;
  }
}
