import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ActivatedRoute, Router } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';
import { FormsModule } from '@angular/forms';
import { Convert as time_slotsCvt, TimeSlots } from '../model/time_slots.model';
import { Convert as stadiumCvt, Stadium } from '../model/stadium.model';
import { Convert as stadiumidCvt, Stadiumid } from '../model/stadiumid.model';
import { AuthService } from '../service/AuthService.service';
@Component({
  selector: 'app-admin-edittime',
  standalone: true,
  imports: [RouterLink, CommonModule, FormsModule],
  providers: [DataServiceService],
  templateUrl: './admin-edittime.component.html',
  styleUrls: ['./admin-edittime.component.scss']
})
export class AdminEdittimeComponent implements OnInit {
  IdNumber: number = 0;
  Stadiumids: Stadiumid[] = [];
  idStadium: number | undefined;
  time_slots: TimeSlots[] = [];
  booking_date: string = '';
  id_member: number = 0;
  selectedTimes: { [key: number]: boolean } = {};
  Idstadiums: number = 0;
  constructor(
    private dataService: DataServiceService,
    private httpClient: HttpClient,
    private router: Router,
    private rou: ActivatedRoute,
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
      const userData = localStorage.getItem('userData');
      this.loadTimeSlots();
      if (userData) {
        const user = JSON.parse(userData);
        this.id_member = user.id_member;
        this.IdNumber = parseInt(Params['id_number']) || 0;
        if (this.IdNumber !== 0) {
          this.loadIdNumber();
        }
      }
    });
  }

  loadIdNumber(): void {
    this.httpClient
      .get(this.dataService.apiEndpoint + '/stadium_number/' + this.IdNumber)
      .subscribe((data: any) => {
        const jsonString = JSON.stringify(data);
        this.Stadiumids = stadiumidCvt.toStadiumid(jsonString);
        if (this.Stadiumids.length > 0) {
          this.idStadium = this.Stadiumids[0].id_stadium;
          this.loadStadiumName(this.idStadium);
        }
      });
  }


  loadStadiumName(id_stadium: number): void {
    this.httpClient
      .get(this.dataService.apiEndpoint + '/stadium/' + this.Idstadiums)
      .subscribe((data: any) => {
        const stadiumName = data.stadium_name;
        this.Stadiumids.forEach(stadium => {
          if (stadium.id_stadium === id_stadium) {
            stadium.stadium_name = stadiumName;
          }
        });
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

  onTimeSlotChange(timeSlotId: number, event: any): void {
    this.selectedTimes[timeSlotId] = event.target.checked;
  }

  Adminbooking(booking_date: string, id_stadium: number, id_number: number, selectedTimes: { [key: number]: boolean }): void {
    if (!booking_date) {
        Swal.fire({
            title: 'กรุณาเลือกวันที่!',
            text: 'คุณต้องเลือกวันที่ก่อนทำการปิดใช้งานสนาม',
            icon: 'warning',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#198b75',
        });
        return; 
    }

    const formData = new FormData();
    formData.append('booking_date', booking_date);
    formData.append('id_stadium', id_stadium.toString());
    formData.append('id_number', id_number.toString());
    formData.append('id_member', this.id_member.toString());

    const selectedTimeSlots = Object.keys(selectedTimes).filter(key => selectedTimes[+key]).join(',');
    formData.append('id_time', selectedTimeSlots);

    const endpoint = `${this.dataService.apiEndpoint}/admin_booking`;

    this.httpClient.post(endpoint, formData).subscribe(
      (response: any) => {
        Swal.fire({
          title: 'ปิดช่วงเวลาการใช้งานสนามสำเร็จ!',
          text: 'คุณได้ทำการปิดการใช้งานสนามสำเร็จ',
          icon: 'success',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        }).then((result) => {
          if (result.isConfirmed) {
            this.router.navigate(['/admin-allnumber'], { queryParams: { id_stadium: id_stadium } });
          }
        });
      },
      (error) => {
        Swal.fire({
          title: 'คุณได้ปิดการจองใช้สนามนี้ไปแล้ว!',
          text: 'เกิดข้อผิดพลาดในการปิดใช้งานสนาม',
          icon: 'error',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        });
      }
    );
}

}
