import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ActivatedRoute, Router } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';
import { Convert as MemberConverter, Profileshow } from '../model/profile.model';

@Component({
  selector: 'app-navbar-admin',
  standalone: true,
  imports: [RouterLink, CommonModule],
  templateUrl: './navbar-admin.component.html',
  styleUrls: ['./navbar-admin.component.scss']
})
export class NavbarAdminComponent implements OnInit {
  IdMember: number = 0;
  profileshow: Profileshow[] = [];
  isAdmin: boolean = false;
  isSidebarActive: boolean = true; // ตัวแปรสำหรับเก็บสถานะของ Sidebar
  subMenuActive: string | null = null;
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

  activeMenu: string = 'หน้าหลัก';  // เก็บเมนูที่ถูกเลือก

  setActiveMenu(menu: string) {
    this.activeMenu = menu;
  }
  
  isActive(menu: string): boolean {
    return this.activeMenu === menu;
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
  toggleSidebar() {
    this.isSidebarActive = !this.isSidebarActive;
  }
  
  toggleSubMenu(menu: string) {
    this.subMenuActive = this.subMenuActive === menu ? null : menu;
  }
  
  isSubMenuActive(menu: string): boolean {
    return this.subMenuActive === menu;
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
