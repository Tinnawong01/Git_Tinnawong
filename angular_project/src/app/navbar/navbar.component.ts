import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ActivatedRoute, Router } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';
import { Convert as MemberConverter, Profileshow } from '../model/profile.model';

@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [RouterLink, CommonModule],
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.scss']
})
export class NavbarComponent implements OnInit {
  IdMember: number = 0;
  profileshow: Profileshow[] = [];
  isAdmin: boolean = false;

  constructor(
    private httpClient: HttpClient,
    private router: Router,
    private rou: ActivatedRoute,
    private dataService: DataServiceService
  ) { }

  ngOnInit(): void {
    const userData = localStorage.getItem('userData');
    if (userData) {
      const user = JSON.parse(userData);
      this.IdMember = user.id_member;
      this.isAdmin = user.role === 'admin';
      this.loadMemberDetails();
    }
  }

  loadMemberDetails(): void {
    this.httpClient
      .get<Profileshow>(`${this.dataService.apiEndpoint}/memberid/${this.IdMember}`)
      .subscribe(
        (data: Profileshow) => {
          this.profileshow = [data];
        },
        (error) => {
          console.error('Failed to load member details', error);
        }
      );
  }

  Memberid(id_member: number) {
    this.router.navigate(['/profile-main'], {
      queryParams: {
        id_member: id_member
      }
    });
  }

  logout() {
    Swal.fire({
      title: 'คุณต้องการออกระบบใช่หรือไม่?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'ยืนยัน',
      cancelButtonText: 'ยกเลิก',
      confirmButtonColor: '#198b75',
    }).then((result) => {
      if (result.isConfirmed) {
        localStorage.removeItem('isLoggedIn');
        localStorage.removeItem('userData');
        this.router.navigate(['/login']).then(() => {
          location.reload();
        });
      }
    });
  }
}