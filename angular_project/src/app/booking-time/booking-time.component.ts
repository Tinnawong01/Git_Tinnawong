import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';
import { FormsModule } from '@angular/forms';
import { Convert as stadiumCvt, Stadium } from '../model/stadium.model';
import { Convert as stadiumidCvt, Stadiumid } from '../model/stadiumid.model';
import { Convert as time_slotsCvt, TimeSlots } from '../model/time_slots.model';
import { Convert as stadiumshowCvt, Stadiumshow } from '../model/stadiumshow.model';
import { Convert as BookingUserCvt, BookingUser } from '../model/booking_user.model';
import { AuthService } from '../service/AuthService.service';
@Component({
  selector: 'app-booking-time',
  standalone: true,
  imports: [CommonModule, FormsModule],
  providers: [DataServiceService],
  templateUrl: './booking-time.component.html',
  styleUrls: ['./booking-time.component.scss']
})
export class BookingTimeComponent implements OnInit {
  Idstadiums: number = 0;
  Stadiums: Stadium[] = [];
  Stadiumids: Stadiumid[] = [];
  time_slots: TimeSlots[] = [];
  selectedDate: string = '';
  id_member: number = 0;
  selectedStadiumId: number = 0;
  Stadiumshow: Stadiumshow[] = [];
  BookingUser: BookingUser[] = [];
  minDate: string = '';
  isSearched: boolean = false;

  constructor(
    private dataService: DataServiceService,
    private httpClient: HttpClient,
    private router: Router,
    private rou: ActivatedRoute,
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
    this.setMinDate();
    this.rou.queryParams.subscribe((Params) => {
      const userData = localStorage.getItem('userData');

      if (userData) {
        const user = JSON.parse(userData);
        this.Idstadiums = parseInt(Params['id_stadium']) || 0;
        this.selectedDate = Params['date'] || '';
        if (this.Idstadiums !== 0) {
          this.loadidstadium();
        }
        this.id_member = user.id_member;
      }

      this.httpClient
        .get(this.dataService.apiEndpoint + '/stadium')
        .subscribe((data: any) => {
          this.Stadiums = stadiumCvt.toStadium(JSON.stringify(data));
        });

      this.httpClient
        .get(this.dataService.apiEndpoint + '/stadium_user')
        .subscribe((data: any) => {
          this.Stadiumshow = stadiumshowCvt.toStadiumshow(JSON.stringify(data));
        });

      //      this.loadBookingData();
      this.loadTimeSlots();
      this.loadNumberData();
    });
  }

  setMinDate(): void {
    const today = new Date();
    const year = today.getFullYear();
    const month = ('0' + (today.getMonth() + 1)).slice(-2);
    const day = ('0' + today.getDate()).slice(-2);
    this.minDate = `${year}-${month}-${day}`;
  }

  loadidstadium(): void {
    this.httpClient
      .get(this.dataService.apiEndpoint + '/stadium/' + this.Idstadiums)
      .subscribe((data: any) => {
        const jsonString = JSON.stringify(data);
        this.Stadiumids = stadiumidCvt.toStadiumid(jsonString);
      });
  }

  loadTimeSlots(): void {
    this.httpClient
      .get(this.dataService.apiEndpoint + '/time_slot')
      .subscribe((data: any) => {
        const jsonString = JSON.stringify(data);
        this.time_slots = time_slotsCvt.toTimeSlots(jsonString);
      });
  }

  loadNumberData(): void {
    this.httpClient
      .get(this.dataService.apiEndpoint + '/tb_booking_show')
      .subscribe((data: any) => {
        this.BookingUser = BookingUserCvt.toBookingUser(JSON.stringify(data));
      });
  }

  searchBookingTime(): void {
    if (this.selectedStadiumId && this.selectedDate) {
      this.router.navigate(['/booking-time'], {
        queryParams: {
          id_stadium: this.selectedStadiumId,
          date: this.selectedDate
        }
      });
    } else {
      Swal.fire({
        icon: 'warning',
        title: 'กรุณาเลือกสนามกีฬาและวันที่',
        text: 'กรุณาเลือกสนามกีฬาและวันที่ก่อนทำการค้นหา',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75'
      });
    }
  }

  handleBookingClick(id_time: number, id_number: number, id_stadium: number): void {
    if (!this.selectedDate) {
      Swal.fire({
        icon: 'warning',
        title: 'กรุณาเลือกวันที่',
        text: 'กรุณาเลือกวันที่ก่อนทำการจอง',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75'
      });
      return;
    }
  
    const currentDateTime = new Date(); // บันทึกวันที่และเวลาปัจจุบัน
    const currentDateString = currentDateTime.toISOString().slice(0, 10); // แปลงวันที่เป็นรูปแบบ YYYY-MM-DD
    const currentTimeString = currentDateTime.toTimeString().slice(0, 8); // แปลงเวลาเป็นรูปแบบ HH:MM:SS
  
    if (!this.isBooked(id_time, id_number)) {
      this.router.navigate(['/booking-confirm'], {
        queryParams: {
          id_stadium: id_stadium,
          id_number: id_number,
          booking_date: this.selectedDate,
          id_time: id_time,
          id_member: this.id_member,
          present_date: currentDateString,  // ส่งผ่านค่าของวันที่ปัจจุบันไปใน URL
          present_time: currentTimeString   // ส่งผ่านค่าของเวลาปัจจุบันไปใน URL
        }
      });
    }
  }
  

  searchBookingData(id_time: number, id_number: number, id_stadium: number): void {
    this.router.navigate(['/booking-confirm'], {
      queryParams: {
        id_stadium: id_stadium,
        id_number: id_number,
        booking_date: this.selectedDate,
        id_time: id_time,
        id_member: this.id_member,
      }
    });
  }

  formatDate(date: string): string {
    if (!date) {
      return 'กรุณาเลือกวันที่';
    }
  
    const selectedDate = new Date(date);
    if (isNaN(selectedDate.getTime())) {
      return 'กรุณาเลือกวันที่';
    }
  
    const day = selectedDate.getDate();
    const monthNamesThai = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
      "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
    const month = monthNamesThai[selectedDate.getMonth()];
    const year = selectedDate.getFullYear() + 543;
    return `${day} ${month} ${year}`;
  }
  isPastTime(id_time: number, id_stadium: number): boolean {
    const selectedDate = new Date(this.selectedDate);
    const currentTime = new Date();
    const isSameDay = selectedDate.toDateString() === currentTime.toDateString();
    const isPast = id_time <= this.getCurrentTimeSlot();
    return isSameDay && isPast;
  }
  
  
  isBooked(id_time: number, id_stadium: number): boolean {
    const selectedDate = new Date(this.selectedDate);
    const bookingExists = this.BookingUser.some(booking => {
      const bookingDate = new Date(booking.booking_date);
      return bookingDate.toDateString() === selectedDate.toDateString() &&
        booking.id_time === id_time &&
        booking.id_number === id_stadium;
    });
    return bookingExists;
  }
  

  // ฟังก์ชันสำหรับตรวจสอบช่วงเวลาปัจจุบัน
  getCurrentTimeSlot(): number {
    const currentHour = new Date().getHours();
    if (currentHour < 8) return 0;
    if (currentHour < 9) return 1;
    if (currentHour < 10) return 2;
    if (currentHour < 11) return 3;
    if (currentHour < 12) return 4;
    if (currentHour < 13) return 5;
    if (currentHour < 14) return 6;
    if (currentHour < 15) return 7;
    return 8; 
  }
}
