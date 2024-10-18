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
  selector: 'app-admin-main',
  standalone: true,
  imports: [NgChartsModule, RouterLink, CommonModule, FormsModule],
  templateUrl: './admin-main.component.html',
  styleUrls: ['./admin-main.component.scss']
})
export class AdminMainComponent implements OnInit {
  loading: boolean = false;
  Stadiumshow: Stadiumshow[] = [];
  selectedStadiumId: number = 0; // สำหรับเก็บสนามกีฬาที่เลือก
  totalBookings: number = 0; // จำนวนการจองทั้งหมด
  totalBookinguse: number = 0; // จำนวนการจองทั้งหมด
  totalMembers: number = 0; // จำนวนสมาชิกทั้งหมด
  totalStadiums: number = 0;
  totalNumbers: number = 0;
  selectedYear: number = new Date().getFullYear(); // ค่าเริ่มต้นเป็นปีปัจจุบัน
  availableYears: number[] = []; // Array เก็บปีที่เลือกได้
  months: string[] = [];
  selectedMonth: string = ''; // ตัวแปรสำหรับเก็บเดือนที่เลือก
  bookingData: BookingChart[] = []; // ตัวแปรเก็บข้อมูล
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
    this.loadMonths(); // โหลดเดือนที่ไม่มีปี
    this.loadAvailableYears(); // โหลดปีที่มีให้เลือก
    this.selectedMonth = this.months[0]; // ตั้งค่าเดือนเริ่มต้นเป็น "มกราคม"
    this.fetchBookingData();
    // เรียกดูข้อมูลการจองทั้งหมด
    this.updateChartAllBookings(); 
    

    // ส่งคำร้องขอ HTTP GET สำหรับจำนวนการเข้าใช้งานสนามทั้งหมด
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
          // ส่งคำร้องขอ HTTP GET สำหรับจำนวนการจองทั้งหมด
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

    // ส่งคำร้องขอ HTTP GET สำหรับจำนวนสมาชิกทั้งหมด
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

    // ส่งคำร้องขอ HTTP GET สำหรับสนามกีฬา
    this.httpClient.get(this.dataService.apiEndpoint + '/stadium_user')
      .subscribe((data: any) => {
        // แปลงข้อมูล
        this.Stadiumshow = stadiumshowCvt.toStadiumshow(JSON.stringify(data));
        // แสดงข้อมูลเริ่มต้นสำหรับสนามกีฬาแรก
        if (this.Stadiumshow.length > 0) {
          this.selectedStadiumId = this.Stadiumshow[0].id_stadium;
        }
      });
  }
  
  fetchBookingData() {
    this.httpClient.get<BookingChart[]>(`${this.dataService.apiEndpoint}/graph_booking_sum`)
      .subscribe({
        next: (data) => {
          this.bookingData = data; // เก็บข้อมูลที่ดึงมา
        },
        error: (err) => {
          console.error('Error fetching booking data:', err);
          this.bookingData = []; // ตั้งค่าเป็นอาเรย์ว่างหากเกิดข้อผิดพลาด
        }
      });
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
          this.resetChartData(); // Ensure this method exists
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
    console.log('Selected Date:', selectedDate); // ตรวจสอบค่าที่ถูกเลือก
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
    // แปลงวันที่ที่ได้รับมาเป็นรูปแบบ ISO 8601
    const formattedDate = new Date(selectedDate).toISOString().split('T')[0]; // ตัวอย่างการฟอร์แมต
    console.log('Formatted Date:', formattedDate); // ตรวจสอบรูปแบบวันที่ที่ส่งไป
    this.fetchChartData(formattedDate);
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
