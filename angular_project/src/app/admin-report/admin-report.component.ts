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
  selector: 'app-admin-report',
  standalone: true,
  imports: [NgChartsModule, RouterLink, CommonModule, FormsModule],
  templateUrl: './admin-report.component.html',
  styleUrls: ['./admin-report.component.scss']
})
export class AdminReportComponent implements OnInit {
  currentDate: string = '';
  Stadiumshow: Stadiumshow[] = [];
  selectedStadiumId: number = 0; // สำหรับเก็บสนามกีฬาที่เลือก
  totalBookings: number = 0; // จำนวนการจองทั้งหมด
  totalBookinguse: number = 0; // จำนวนการจองทั้งหมด
  totalMembers: number = 0; // จำนวนสมาชิกทั้งหมด
  totalStadiums: number = 0;
  totalNumbers: number = 0;
  selectedYear: number = new Date().getFullYear(); // ค่าเริ่มต้นเป็นปีปัจจุบัน
  availableYears: number[] = []; // Array เก็บปีที่เลือกได้

  public barChartOptions: ChartOptions<'bar'> = {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          stepSize: 1, // กำหนดให้การแสดงผลเป็นทีละ 1 หน่วย
          callback: function(value) {
            return Number(value).toFixed(0); // ตัดทศนิยมออก
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
    labels: [], // ต้องเป็น array ของ strings
    datasets: [
      {
        data: [], // จำนวนการจอง
        label: 'จำนวนการจอง',
        backgroundColor: 'rgba(231, 76, 60, 0.6)', 
        borderColor: 'rgba(231, 76, 60, 0.8)', 
        borderWidth: 1
      },
      {
        data: [], // จำนวนการเข้าใช้งาน
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
    private cdr: ChangeDetectorRef, // เพิ่ม ChangeDetectorRef
    private authService: AuthService // เพิ่ม AuthService
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
  
    this.loadAvailableYears(); // โหลดปีที่เลือกได้
    this.loadInitialData(); // โหลดข้อมูลเริ่มต้น
    const today = new Date();
    this.currentDate = today.toISOString().split('T')[0];
  }
  
  loadInitialData(): void {
    const currentDate = new Date(); // วันที่ปัจจุบัน
    const selectedDate = currentDate.toISOString().split('T')[0]; // แปลงเป็นรูปแบบ YYYY-MM-DD
  
    this.httpClient.get<{ total_bookinguse: number }>(this.dataService.apiEndpoint + '/bookinguse_count')
      .subscribe({
        next: (data) => {
          this.totalBookinguse = data.total_bookinguse;
        },
        error: (err) => {
          console.error('Error fetching total bookings:', err);
          this.totalBookinguse = 0;
        }
      });
  
    this.httpClient.get<{ total_bookings: number }>(this.dataService.apiEndpoint + '/booking_count')
      .subscribe({
        next: (data) => {
          this.totalBookings = data.total_bookings;
        },
        error: (err) => {
          console.error('Error fetching total bookings:', err);
          this.totalBookings = 0;
        }
      });
  
    this.httpClient.get<{ total_members: number }>(this.dataService.apiEndpoint + '/member_count')
      .subscribe({
        next: (data) => {
          this.totalMembers = data.total_members;
        },
        error: (err) => {
          console.error('Error fetching total members:', err);
          this.totalMembers = 0;
        }
      });
  
    this.httpClient.get<{ total_stadiums: number, total_numbers: number }>(this.dataService.apiEndpoint + '/stadium_and_number_count')
      .subscribe({
        next: (data) => {
          this.totalStadiums = data.total_stadiums;
          this.totalNumbers = data.total_numbers;
        },
        error: (err) => {
          console.error('Error fetching data:', err);
          this.totalStadiums = 0;
          this.totalNumbers = 0;
        }
      });
  
    this.httpClient.get(this.dataService.apiEndpoint + '/stadium_user')
      .subscribe((data: any) => {
        this.Stadiumshow = stadiumshowCvt.toStadiumshow(JSON.stringify(data));
        if (this.Stadiumshow.length > 0) {
          this.selectedStadiumId = this.Stadiumshow[0].id_stadium;
        }
        this.updateChart(selectedDate); // เรียกใช้ updateChart กับวันที่ปัจจุบัน
      });
  }
  

  loadAvailableYears(): void {
    const currentYear = new Date().getFullYear();
    for (let year = currentYear; year >= currentYear - 5; year--) {
      this.availableYears.push(year);
    }
  }
onDateChange(event: any): void {
  const selectedDate = event.target.value; // รับวันที่จาก input
  console.log('Selected Date:', selectedDate); // เพิ่ม log เพื่อตรวจสอบวันที่
  if (selectedDate) {
    this.updateChart(selectedDate); // เรียกใช้ updateChart กับวันที่ที่เลือก
  }
}

updateChart(selectedDate: string): void {
  this.httpClient.get<BookingChart[]>(`${this.dataService.apiEndpoint}/graph_booking_new/${selectedDate}`)
    .subscribe({
      next: (data) => {
        console.log('Data received from API:', data); // เพิ่ม log เพื่อตรวจสอบข้อมูลที่ได้รับ
        if (data && Array.isArray(data) && data.length > 0) {
          const labels = data.map(item => item.stadium_name); 
          const usageValues = data.map(item => item.booking_count); 
          const bookingValues = data.map(item => item.booking_total); 

          this.barChartData = {
            labels: labels,
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
          Swal.fire({
            icon: 'info',
            title: 'ไม่มีข้อมูลการจอง',
            text: 'ไม่มีข้อมูลการจองในวันที่เลือก'
          });
        }
      },
      error: (err) => {
        console.error('Error fetching data:', err);
        this.resetChartData();
        Swal.fire({
          icon: 'error',
          title: 'เกิดข้อผิดพลาด',
          text: 'ไม่สามารถดึงข้อมูลได้'
        });
      }
    });
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
