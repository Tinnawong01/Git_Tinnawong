import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ActivatedRoute, Router } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';
import { FormsModule } from '@angular/forms';
import { Convert as BookingCvt, Bookingid } from '../model/booking.model';
import { AuthService } from '../service/AuthService.service';
import * as QRCode from 'qrcode';
import { Location } from '@angular/common'; 
@Component({
  selector: 'app-admin-approve',
  standalone: true,
  imports: [RouterLink, CommonModule, FormsModule],
  providers: [DataServiceService],
  templateUrl: './admin-approve.component.html',
  styleUrls: ['./admin-approve.component.scss']
})
export class AdminApproveComponent implements OnInit {
  bookings: Bookingid[] = [];
  isDataLoaded = false;
  filteredBookings: Bookingid[] = [];
  confirmationCode: string = ''; 
  constructor(
    private dataService: DataServiceService,
    private httpClient: HttpClient,
    private router: Router,
    private rou: ActivatedRoute,
    private authService: AuthService,
    private location: Location 
  ) {}

  ngOnInit(): void {
    this.rou.queryParamMap.subscribe(params => {
      const id_booking = params.get('id_booking');
      if (id_booking) {
        this.loadBookings(+id_booking);
      } else {
        console.error('No id_booking found in the route parameters.');
      }
    });
  }

  generateQRCode(id_booking: number): Promise<string> {
    return QRCode.toDataURL(`https://tinnawong.bowlab.net/admin-approve?id_booking=${id_booking}`, { width: 200 })
      .catch((err: Error) => {
        console.error('Error generating QR code:', err);
        return ''; 
      });
  }

  loadBookings(id_booking: number): void {
    this.httpClient
      .get<Bookingid[]>(`${this.dataService.apiEndpoint}/report_booking/${id_booking}`)
      .subscribe(
        (data: Bookingid[]) => {
          console.log('Data from API:', data); // เพิ่มการตรวจสอบข้อมูลที่ได้รับจาก API
          this.bookings = data.map(item => ({
            id_booking: item.id_booking,
            booking_date: new Date(item.booking_date),
            present_date: item.present_date ? new Date(item.present_date) : undefined,
            present_time: item.present_time || '',
            stadium_name: item.stadium_name,
            number_name: item.number_name,
            time: item.time,
            fname: item.fname,
            lname: item.lname,
            booking_status_label: item.booking_status_label,
            qrCodeUrl: ''
          }));
          this.filteredBookings = [...this.bookings];
          this.isDataLoaded = true;
  
          this.bookings.forEach(booking => {
            this.generateQRCode(booking.id_booking).then(url => {
              booking.qrCodeUrl = url;
            });
          });
        },
        (error: any) => {
          console.error('Error loading bookings:', error);
        }
      );
  }
  
formattedDate(date?: Date): string {
  if (!date) {
    return 'ไม่ระบุวันที่'; // ค่าเริ่มต้นหากวันที่เป็น undefined
  }
  const options: Intl.DateTimeFormatOptions = { day: 'numeric', month: 'long', year: 'numeric' };
  return new Date(date).toLocaleDateString('th-TH', options);
}

approveBooking(id_booking: number): void {
  Swal.fire({
    title: 'ยืนยันการเข้าใช้สนามใช่หรือไม่?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'ยืนยัน',
    cancelButtonText: 'ยกเลิก',
    confirmButtonColor: '#198b75'
  }).then((result) => {
    if (result.isConfirmed) {
      this.httpClient
        .put(`${this.dataService.apiEndpoint}/approve_booking/${id_booking}`, {})
        .subscribe(
          response => {
            console.log('Booking approved successfully:', response);
            Swal.fire(
              'สำเร็จ!',
              'การยืนยันการใช้งานได้ถูกบันทึกแล้ว',
              'success'
            ).then(() => {
              this.router.navigate(['/']).then(() => {
                location.reload();  
              });
            });
          },
          (error: any) => {
            console.error('Error approving booking:', error);
            Swal.fire(
              'ข้อผิดพลาด!',
              'ไม่สามารถอนุมัติการจองได้',
              'error'
            );
          }
        );
    }
  });
}

}
