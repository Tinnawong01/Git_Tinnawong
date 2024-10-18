import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ActivatedRoute, Router } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';
import { Convert as BookingUserConverter, BookingUser } from '../model/booking_user.model';
import { AuthService } from '../service/AuthService.service';

@Component({
  selector: 'app-booking-my',
  standalone: true,
  imports: [RouterLink, CommonModule],
  providers: [DataServiceService],
  templateUrl: './booking-my.component.html',
  styleUrls: ['./booking-my.component.scss']
})
export class BookingMyComponent implements OnInit {
  BookingUserid: BookingUser[] = [];
  id_member: number = 0;

  constructor(
    private httpClient: HttpClient,
    private rou: ActivatedRoute,
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

    this.rou.queryParams.subscribe((Params) => {
      this.id_member = parseInt(Params['id_member']) || 0;
      if (this.id_member !== 0) {
        this.loadProfile();
      }
    });
  }

  loadProfile(): void {
    if (this.id_member !== 0) {
      this.httpClient
        .get(`${this.dataService.apiEndpoint}/report_bookinguser/${this.id_member}`)
        .subscribe((data: any) => {
          this.BookingUserid = data;
        });
    }
  }

  formattedDate(date: Date): string {
    const options: Intl.DateTimeFormatOptions = { day: 'numeric', month: 'long', year: 'numeric' };
    return new Date(date).toLocaleDateString('th-TH', options);
  }

  confirmDeletebooking(id_booking: number): void {
    Swal.fire({
      title: 'คุณต้องการยกเลิกการจองสนามนี้ ?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#198b75',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ใช่, ยกเลิกเลย!',
      cancelButtonText: 'ยกเลิก'
    }).then((result) => {
      if (result.isConfirmed) {
        this.deletebooking(id_booking);
      }
    });
  }
  canCancelBooking(bookingDate: Date): boolean {
    const bookingTime = new Date(bookingDate);
    bookingTime.setHours(bookingTime.getHours() - 2); // ตั้งเวลายกเลิก 2 ชั่วโมงก่อน
    return new Date() < bookingTime; // คืนค่าจริงถ้ายังสามารถยกเลิกได้
}

  deletebooking(id_booking: number): void {
    const apiUrl = `${this.dataService.apiEndpoint}/delete_booking/${id_booking}`;
    this.httpClient.delete(apiUrl).subscribe(
      (response: any) => {
        Swal.fire({
          title: 'คุณได้ทำการยกเลิกการจองเสร็จสิ้น',
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
        Swal.fire({
          title: 'ไม่สามารถยกเลิกการจองสนามได้!',
          text: 'เนื่องจากเกินช่วงเวลาก่อนเริ่มใช้งานสนาม 2 ชั่วโมง',
          icon: 'error',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        });
      }
    );
  }
}
