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
  selector: 'app-admin-alltime',
  standalone: true,
  imports: [RouterLink, CommonModule],
  providers: [DataServiceService],
  templateUrl: './admin-alltime.component.html',
  styleUrl: './admin-alltime.component.scss'
})
export class AdminAlltimeComponent implements OnInit {
  BookingUserid: BookingUser[] = [];
  displayedBookings: BookingUser[] = [];
  currentPage: number = 1;
  itemsPerPage: number = 7;
  totalItems: number = 0;
  id_member: number = 0;

  constructor(
    private httpClient: HttpClient,
    private rou: ActivatedRoute,
    private router: Router,
    private dataService: DataServiceService,
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
      this.id_member = parseInt(Params['id_member']) || 0;
      if (this.id_member !== 0) {
        this.loadProfile();
      }
    });
  }

  loadProfile(): void {
    if (this.id_member !== 0) {
      this.httpClient
        .get(`${this.dataService.apiEndpoint}/report_bookingadmin/${this.id_member}`)
        .subscribe((data: any) => {
          this.BookingUserid = data;
          this.totalItems = this.BookingUserid.length;
          this.updateDisplayedBookings();
        });
    }
  }

  updateDisplayedBookings(): void {
    const startIndex = (this.currentPage - 1) * this.itemsPerPage;
    const endIndex = startIndex + this.itemsPerPage;
    this.displayedBookings = this.BookingUserid.slice(startIndex, endIndex);
  }

  totalPages(): number[] {
    return Array(Math.ceil(this.totalItems / this.itemsPerPage)).fill(0).map((x, i) => i + 1);
  }

  setPage(page: number): void {
    this.currentPage = page;
    this.updateDisplayedBookings();
  }

  nextPage(): void {
    if (this.currentPage < this.totalPages().length) {
      this.currentPage++;
      this.updateDisplayedBookings();
    }
  }

  previousPage(): void {
    if (this.currentPage > 1) {
      this.currentPage--;
      this.updateDisplayedBookings();
    }
  }

  formattedDate(date: Date): string {
    const options: Intl.DateTimeFormatOptions = { day: 'numeric', month: 'long', year: 'numeric' };
    return new Date(date).toLocaleDateString('th-TH', options);
  }

  confirmDeletebooking(id_booking: number): void {
    Swal.fire({
      title: 'ยกเลิกการปิดใช้สนามนี้?',
      icon: 'warning',
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

  deletebooking(id_booking: number): void {
    const apiUrl = `${this.dataService.apiEndpoint}/delete_bookingadmin/${id_booking}`;
    this.httpClient.delete(apiUrl).subscribe(
      (response: any) => {
        Swal.fire({
          title: 'ยกเลิกการปิดใช้สนามนี้สำเร็จ!',
          text: 'คุณได้ทำการยกเลิกการใช้สนามนี้สำเร็จ',
          icon: 'success',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        }).then((result) => {
          if (result.isConfirmed) {
            this.loadProfile();
          }
        });
      },
      (error) => {
        Swal.fire({
          title: 'ลบข้อมูลไม่สำเร็จ!',
          text: 'คุณได้ทำการลบข้อมูลไม่สำเร็จ',
          icon: 'error',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        });
      }
    );
  }

  confirmCancelAll(): void {
    Swal.fire({
      title: 'ยกเลิกการปิดใช้สนามทั้งหมด?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#198b75',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ใช่, ยกเลิกทั้งหมด!',
      cancelButtonText: 'ยกเลิก'
    }).then((result) => {
      if (result.isConfirmed) {
        this.cancelAllBookings();
      }
    });
  }

  cancelAllBookings(): void {
    const apiUrl = `${this.dataService.apiEndpoint}/delete_bookingall/${this.id_member}`;
    this.httpClient.delete(apiUrl).subscribe(
      (response: any) => {
        Swal.fire({
          title: 'ยกเลิกการปิดใช้สนามทั้งหมดสำเร็จ!',
          text: 'คุณได้ทำการยกเลิกการปิดใช้สนามทั้งหมดสำเร็จ',
          icon: 'success',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        }).then((result) => {
          if (result.isConfirmed) {
            this.loadProfile();
          }
        });
      },
      (error) => {
        Swal.fire({
          title: 'ลบข้อมูลไม่สำเร็จ!',
          text: 'คุณได้ทำการลบข้อมูลไม่สำเร็จ',
          icon: 'error',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        });
      }
    );
  }
}
