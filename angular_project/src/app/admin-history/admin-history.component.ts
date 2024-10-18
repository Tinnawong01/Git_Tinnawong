import { Component, OnInit, AfterViewInit, HostListener } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ActivatedRoute, Router } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';
import { FormsModule } from '@angular/forms';
import { Convert as BookingCvt, Bookingid } from '../model/booking.model';
import { AuthService } from '../service/AuthService.service';
declare const simpleDatatables: any;

@Component({
  selector: 'app-admin-history',
  standalone: true,
  imports: [RouterLink, CommonModule, FormsModule],
  providers: [DataServiceService],
  templateUrl: './admin-history.component.html',
  styleUrls: ['./admin-history.component.scss']
})
export class AdminHistoryComponent implements OnInit, AfterViewInit {
  bookings: Bookingid[] = [];
  filteredBookings: Bookingid[] = [];
  searchTerm: string = '';
  currentPage: number = 1;
  pageSize: number = 100; 
  isLargeScreen: boolean = window.innerWidth > 700;
  dataTable: any;
  isDataLoaded: boolean = false;

  constructor(
    private dataService: DataServiceService,
    private httpClient: HttpClient,
    private router: Router,
    private rou: ActivatedRoute,
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

    this.rou.queryParams.subscribe((params) => {
      this.loadBookings();
    });

    this.updateScreenSize();
  }

  ngAfterViewInit(): void {
    if (this.isDataLoaded) {
      this.initDataTable();
    } else {
      const interval = setInterval(() => {
        if (this.isDataLoaded) {
          this.initDataTable();
          clearInterval(interval);
        }
      }, 100);
    }
  }  

  paginatedBookings(): Bookingid[] {
    const startIndex = (this.currentPage - 1) * this.pageSize;
    return this.filteredBookings.slice(startIndex, startIndex + this.pageSize);
  }
  
  loadBookings(): void {
    this.httpClient
      .get<any[]>(this.dataService.apiEndpoint + '/report_booking')
      .subscribe(
        (data: any[]) => {
          this.bookings = data.map((item) => ({
            id_booking: item.id_booking,
            booking_date: new Date(item.booking_date),
            stadium_name: item.stadium_name,
            number_name: item.number_name,
            time: item.time,
            fname: item.fname,
            lname: item.lname,
            booking_status_label: item.booking_status_label
          }));
          this.filteredBookings = [...this.bookings];
          console.log(this.bookings);
          this.isDataLoaded = true;
        },
        (error) => {
          console.error('Error loading bookings:', error);
        }
      );
  }
  
  getStatusClass(status: string): string {
    return status === 'จอง' ? 'tag-color-name-yellow' : 'tag-color-name-green';
  }
  getStatusIcon(status: string): string {
    return status === 'จอง' ? 'fas fa-unlink' : 'fas fa-check-circle';
  }
  
  initDataTable(): void {
    const tableElement = document.querySelector('#table1') as HTMLTableElement;
    if (tableElement) {
      this.dataTable = new simpleDatatables.DataTable(tableElement, {
        searchable: true,
        perPage: 10, 
        perPageSelect: [5, 10, 15, 20, this.bookings.length],
      });

      if (this.dataTable) {
        this.dataTable.on('datatable.init', () => {
          this.dataTable.update();
        });
      }
    }
  }  
  @HostListener('window:resize', ['$event'])
  onResize(event: Event) {
    this.updateScreenSize();
  }

  updateScreenSize() {
    this.isLargeScreen = window.innerWidth > 700;
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
