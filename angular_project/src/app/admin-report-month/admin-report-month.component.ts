import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { ChartOptions, ChartType, ChartData } from 'chart.js';
import { NgChartsModule } from 'ng2-charts';
import { RouterLink } from '@angular/router';
import { ActivatedRoute, Router } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';
import { Convert as stadiumshowCvt, Stadiumshow } from '../model/stadiumshow.model';
import { BookingChart } from '../model/booking_chart.model';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../service/AuthService.service';

@Component({
  selector: 'app-admin-report-month',
  standalone: true,
  imports: [NgChartsModule, RouterLink, CommonModule, FormsModule],
  templateUrl: './admin-report-month.component.html',
  styleUrls: ['./admin-report-month.component.scss']
})
export class AdminReportMonthComponent implements OnInit {
  Stadiumshow: Stadiumshow[] = [];
  selectedStadiumId: number = 0;
  totalBookings: number = 0;
  totalBookinguse: number = 0;
  totalMembers: number = 0;
  totalStadiums: number = 0;
  totalNumbers: number = 0;
  selectedYear: number = new Date().getFullYear();
  availableYears: number[] = [];
  months: string[] = [];
  selectedMonth: string = ''; // ตัวแปรสำหรับเก็บเดือนที่เลือก

  public barChartOptions: ChartOptions<'bar'> = {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          stepSize: 1,
          callback: function(value) {
            return Number(value).toFixed(0);
          }
        }
      }
    },
    plugins: {
      legend: {
        display: true,
        position: 'top',
      }
    }
  };

  public barChartData: ChartData<'bar'> = {
    labels: [],
    datasets: [
      {
        data: [],
        label: 'จำนวนการจอง',
        backgroundColor: 'rgba(231, 76, 60, 0.6)', 
        borderColor: 'rgba(231, 76, 60, 0.8)', 
        borderWidth: 1
      },
      {
        data: [],
        label: 'จำนวนการเข้าใช้งาน',
        backgroundColor: 'rgba(25, 168, 140, 0.6)',
        borderColor: 'rgba(25, 168, 140, 0.8)',
        borderWidth: 1
      }
    ]
  };

  public barChartType: ChartType = 'bar';
  public barChartLegend = true;
  public barChartPlugins = [];

  constructor(
    private dataService: DataServiceService,
    private httpClient: HttpClient,
    private router: Router,
    private rou: ActivatedRoute,
    private cdr: ChangeDetectorRef,
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
  
    this.loadMonths(); // โหลดเดือนที่ไม่มีปี
    this.loadAvailableYears(); // โหลดปีที่มีให้เลือก
    this.selectedMonth = this.months[0]; // ตั้งค่าเดือนเริ่มต้นเป็น "มกราคม"
  
    // เรียกดูข้อมูลการจองทั้งหมด
    this.updateChartAllBookings(); 
  }
  
  updateChartAllBookings(): void {
    // เรียกดูข้อมูลการจองทั้งหมดโดยไม่ต้องระบุปีและเดือน
    this.fetchChartDatas(); // ไม่ต้องส่งพารามิเตอร์
  }
  
  fetchChartDatas(): void {
    this.httpClient.get<BookingChart[]>(`${this.dataService.apiEndpoint}/graph_booking_sum`)
      .subscribe({
        next: (data) => this.processChartData(data),
        error: (err) => {
          console.error('Error fetching data:', err);
          this.resetChartData();
        }
      });
  }
  
  showAllData(): void {
    // เรียกข้อมูลการจองทั้งหมดโดยไม่ต้องระบุวันที่หรือเดือน
    this.fetchChartDatas(); // เรียกใช้ฟังก์ชัน fetchChartData ที่ไม่ต้องส่งพารามิเตอร์
  }
  
  loadAvailableYears(): void {
    const currentYear = new Date().getFullYear();
    for (let year = currentYear; year >= currentYear - 5; year--) {
      this.availableYears.push(year);
    }
  }

  loadMonths(): void {
    const thaiMonths = [
      'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน',
      'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม',
      'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
    ];
  
    // เพิ่มชื่อเดือนเท่านั้น
    this.months = thaiMonths;
  }
  
  onDateChange(event: any): void {
    const selectedDate = event.target.value; 
    console.log('Selected Date:', selectedDate);
    if (selectedDate) {
      this.updateChartByDate(selectedDate); 
    }
  }
  
  onMonthChange(event: any): void {
    const selectedMonth = event.target.value;
    const monthNumber = this.months.indexOf(selectedMonth) + 1; 
  
    if (monthNumber) {
      this.updateChart(this.selectedYear, monthNumber); 
    }
  }
  
  onYearChange(event: any): void {
    this.selectedYear = +event.target.value || new Date().getFullYear(); 
    if (this.selectedMonth) {
      const monthNumber = this.months.indexOf(this.selectedMonth) + 1;
      this.updateChart(this.selectedYear, monthNumber); 
    }
  }
  
  updateChart(selectedYear: number, monthNumber: number): void {
    const filterValue = `${selectedYear}-${monthNumber.toString().padStart(2, '0')}`;
    this.fetchChartData(filterValue);
  }
  
  updateChartByDate(selectedDate: string): void {
    this.fetchChartData(selectedDate);
  }
  fetchChartData(filterValue: string): void {
    this.httpClient.get<BookingChart[]>(`${this.dataService.apiEndpoint}/graph_booking_sum/${filterValue}`)
      .subscribe({
        next: (data) => this.processChartData(data),
        error: (err) => {
          console.error('Error fetching data:', err);
          this.resetChartData();
        }
      });
  }
  processChartData(data: BookingChart[]): void {
    if (data && Array.isArray(data)) {
      const labels = data.map(item => item.stadium_name);
      const usageValues = data.map(item => item.booking_count);
      const bookingValues = data.map(item => item.booking_total);
  
      this.barChartData = {
        labels,
        datasets: [
          {
            data: bookingValues,
            label: 'จำนวนการจอง',
            backgroundColor: 'rgba(231, 76, 60, 0.6)',
            borderColor: 'rgba(231, 76, 60, 0.8)',
            borderWidth: 1
          },
          {
            data: usageValues,
            label: 'จำนวนการเข้าใช้งาน',
            backgroundColor: 'rgba(25, 168, 140, 0.6)',
            borderColor: 'rgba(25, 168, 140, 0.8)',
            borderWidth: 1
          }
        ]
      };
  
      this.cdr.detectChanges();
    } else {
      this.resetChartData();
    }
  }
  
  resetChartData(): void {
    this.barChartData = {
      labels: [],
      datasets: [
        {
          data: [],
          label: 'จำนวนการจอง',
          backgroundColor: 'rgba(231, 76, 60, 0.6)',
          borderColor: 'rgba(231, 76, 60, 0.8)',
          borderWidth: 1
        },
        {
          data: [],
          label: 'จำนวนการเข้าใช้งาน',
          backgroundColor: 'rgba(25, 168, 140, 0.6)',
          borderColor: 'rgba(25, 168, 140, 0.8)',
          borderWidth: 1
        }
      ]
    };
  }
  
}
