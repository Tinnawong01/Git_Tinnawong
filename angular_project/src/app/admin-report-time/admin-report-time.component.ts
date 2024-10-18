import { Component, OnInit, AfterViewInit, HostListener } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ActivatedRoute, Router } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';
import { FormsModule } from '@angular/forms';
import { BookingCount } from '../model/booking_count.model';
import { AuthService } from '../service/AuthService.service';
declare const simpleDatatables: any;

@Component({
  selector: 'app-admin-report-time',
  standalone: true,
  imports: [RouterLink, CommonModule, FormsModule],
  providers: [DataServiceService],
  templateUrl: './admin-report-time.component.html',
  styleUrls: ['./admin-report-time.component.scss']
})
export class AdminReportTimeComponent implements OnInit, AfterViewInit {
  bookings: BookingCount[] = [];
  filteredBookings: BookingCount[] = [];
  selectedTime: string = '';
  noBookingsFound: boolean = false;
  times: any[] = [];
  timess: string[] = [
    '09:00 - 10:00',
    '10:00 - 11:00',
    '11:00 - 12:00',
    '12:00 - 13:00',
    '13:00 - 14:00',
    '14:00 - 15:00',
    '15:00 - 16:00'
  ];
  currentPage: number = 1;
  pageSize: number = 100;
  isLargeScreen: boolean = window.innerWidth > 768;
  dataTable: any;
  isDataLoaded: boolean = false;

  constructor(
    private dataService: DataServiceService,
    private httpClient: HttpClient,
    private router: Router,
    private route: ActivatedRoute,
    private authService: AuthService
  ) {}

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
  
    this.route.queryParams.subscribe((params) => {
      this.httpClient.get<any[]>(this.dataService.apiEndpoint + '/report_booking_time')
        .subscribe((data: any[]) => {
          console.log(data); // ตรวจสอบข้อมูลที่ได้รับ
          this.bookings = data.map(item => ({
            id_booking: item.id_booking,
            stadium_name: item.stadium_name,
            number_name: item.number_name,
            time: item.time,
            count: item.count,
            id_time: item.id_time,
            booking_date: new Date(item.booking_date)
          }));
          this.filteredBookings = this.bookings;
          this.isDataLoaded = true;
          setTimeout(() => this.initDataTable(), 0); // ใช้ setTimeout เพื่อให้แน่ใจว่า DataTable ถูกสร้างหลังจากข้อมูลโหลด
        });
    });
  
    this.updateScreenSize();
  }

  ngAfterViewInit(): void {
    if (this.isDataLoaded) {
      this.initDataTable();
    }
  }
  
  paginatedBookings(): BookingCount[] {
    const startIndex = (this.currentPage - 1) * this.pageSize;
    return this.filteredBookings.slice(startIndex, startIndex + this.pageSize);
  }

  initDataTable(): void {
    const tableElement = document.querySelector('#table1') as HTMLTableElement;
    if (tableElement) {
      console.log('Initializing DataTable');
      this.dataTable = new simpleDatatables.DataTable(tableElement, {
        searchable: true,
        perPage: 10,
        perPageSelect: [5, 10, 15, 20, this.filteredBookings.length],
      });
    
      if (this.dataTable) {
        this.dataTable.on('datatable.init', () => {
          this.dataTable.update();
        });
      }
    } else {
      console.error('Table element not found');
    }
  }
  
  @HostListener('window:resize', ['$event'])
  onResize(event: Event) {
    this.updateScreenSize();
  }

  updateScreenSize() {
    this.isLargeScreen = window.innerWidth > 768;
  }

  onSearch(): void {
    if (this.selectedTime) {
      this.filteredBookings = this.bookings.filter(booking => booking.time === this.selectedTime);
    } else {
      this.filteredBookings = this.bookings;
    }

    if (this.filteredBookings.length === 0) {
      this.noBookingsFound = true;
      this.filteredBookings = [{
        count: 0,
        id_booking: 0,
        stadium_name: 'ไม่มีข้อมูลการใช้สนามกีฬาในช่วงเวลานี้',
        number_name: 'ไม่มีข้อมูลการใช้สนามกีฬาในช่วงเวลานี้',
        time: 'ไม่มีข้อมูลการใช้สนามกีฬาในช่วงเวลานี้',
        id_time: 0,
        booking_date: new Date()
      }];
    } else {
      this.noBookingsFound = false;
    }
  }

  sortBookingsByCountAsc(): void {
    this.filteredBookings.sort((a, b) => a.count - b.count);
  }

  sortBookingsByCountDesc(): void {
    this.filteredBookings.sort((a, b) => b.count - a.count);
  }

  refreshBookings(): void {
    this.httpClient.get<any[]>(this.dataService.apiEndpoint + '/report_booking_time')
      .subscribe((data: any[]) => {
        this.bookings = data.map(item => ({
          id_booking: item.id_booking,
          stadium_name: item.stadium_name,
          number_name: item.number_name,
          time: item.time,
          count: item.count,
          id_time: item.id_time,
          booking_date: new Date(item.booking_date)
        }));
        this.filteredBookings = this.bookings;
        this.refreshDataTable(); 
      });
  }

  setPage(page: number): void {
    this.currentPage = page;
  }

  previousPage(): void {
    if (this.currentPage > 1) {
      this.currentPage--;
    }
  }

  nextPage(): void {
    if (this.currentPage < this.totalPages().length) {
      this.currentPage++;
    }
  }

  totalPages(): number[] {
    return Array(Math.ceil(this.filteredBookings.length / this.pageSize))
      .fill(0)
      .map((_, i) => i + 1);
  }

  formattedDate(date: Date): string {
    const options: Intl.DateTimeFormatOptions = {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
    };
    return new Date(date).toLocaleDateString('th-TH', options);
  }

  refreshDataTable(): void {
    if (this.dataTable) {
      this.dataTable.destroy();
      this.initDataTable();
    }
  }
  
}
