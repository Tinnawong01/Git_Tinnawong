import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ActivatedRoute, Router } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';
import { Convert as ProfileshowConverter, Profileshow } from '../model/profile.model';
import { AuthService } from '../service/AuthService.service';

@Component({
  selector: 'app-profile-name',
  standalone: true,
  imports: [RouterLink, CommonModule],
  providers: [DataServiceService],
  templateUrl: './profile-name.component.html',
  styleUrls: ['./profile-name.component.scss']
})
export class ProfileNameComponent implements OnInit {
  profileshowid: Profileshow[] = [];
  id_member: number = 0;
  fname: string = '';
  lname: string = '';

  constructor(
    private httpClient: HttpClient,
    private route: ActivatedRoute,
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

    this.route.queryParams.subscribe((Params) => {
      this.id_member = parseInt(Params['id_member']) || 0;
      if (this.id_member !== 0) {
        this.loadProfile();
      }
    });
  }

  loadProfile(): void {
    this.httpClient
      .get(`${this.dataService.apiEndpoint}/memberid/${this.id_member}`)
      .subscribe((data: any) => {
        this.profileshowid = [data];
        this.fname = this.profileshowid[0]?.fname || '';
        this.lname = this.profileshowid[0]?.lname || '';
      });
  }

  confirmEditName(fname: string, lname: string): void {
    if (!fname || !lname) {
      Swal.fire({
        title: 'กรุณากรอกชื่อและนามสกุล',
        icon: 'error',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#198b75',
      });
      return;
    }

    Swal.fire({
      title: 'ยืนยันการเเก้ไขชื่อ-นามสกุล',
      text: 'คุณต้องการการเเก้ไขชื่อ-นามสกุลหรือไม่?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'ยืนยัน',
      cancelButtonText: 'ยกเลิก',
      confirmButtonColor: '#198b75',
      cancelButtonColor: '#d33',
    }).then((result) => {
      if (result.isConfirmed) {
        this.EditName(fname, lname);
      }
    });
  }

  EditName(fname: string, lname: string): void {
    const formData = new FormData();
    formData.append('id_member', this.id_member.toString());
    formData.append('fname', fname);
    formData.append('lname', lname);

    const endpoint = `${this.dataService.apiEndpoint}/Edit_profile`;

    this.httpClient.post(endpoint, formData).subscribe(
      (response: any) => {
        console.log('Name updated successfully:', response);
        Swal.fire({
          title: 'เเก้ไขชื่อ-นามสกุลสำเร็จ!',
          text: 'คุณได้ทำการเเก้ไขชื่อ-นามสกุลสำเร็จ',
          icon: 'success',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        }).then(() => {
          this.router.navigate(['/profile-main'], { queryParams: { id_member: this.id_member } });
          location.reload();
        });
      },
      (error) => {
        console.error('Failed to update name:', error);
        Swal.fire({
          title: 'เเก้ไขชื่อ-นามสกุลไม่สำเร็จ!',
          text: 'คุณได้ทำการเเก้ไขชื่อ-นามสกุลไม่สำเร็จ',
          icon: 'error',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#198b75',
        });
      }
    );
  }
}
