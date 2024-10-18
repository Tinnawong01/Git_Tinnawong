import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { NavbarComponent } from './navbar/navbar.component';
import { RegisterComponent } from './register/register.component';
import { StadiumDetailComponent } from './stadium-detail/stadium-detail.component';
import { LoginComponent } from './login/login.component';
import { BookingTimeComponent } from './booking-time/booking-time.component';
import { BookingMyComponent } from './booking-my/booking-my.component';
import { QrCodeComponent } from './qr-code/qr-code.component';
import { BookingConfirmComponent } from './booking-confirm/booking-confirm.component';
import { BookingHistoryComponent } from './booking-history/booking-history.component';
import { ProfileMainComponent } from './profile-main/profile-main.component';
import { ProfileNameComponent } from './profile-name/profile-name.component';
import { ProfilePasswordComponent } from './profile-password/profile-password.component';
import { AdminMainComponent } from './admin-main/admin-main.component';
import { AdminManageComponent } from './admin-manage/admin-manage.component';
import { AdminReportComponent } from './admin-report/admin-report.component';
import { AdminAddtypeComponent } from './admin-addtype/admin-addtype.component';
import { AdminAddnumberComponent } from './admin-addnumber/admin-addnumber.component';
import { AdminEdittypeComponent } from './admin-edittype/admin-edittype.component';
import { AdminAllnumberComponent } from './admin-allnumber/admin-allnumber.component';
import { AdminEditnumberComponent } from './admin-editnumber/admin-editnumber.component';
import { AdminEdittimeComponent } from './admin-edittime/admin-edittime.component';
import { HttpClientModule } from '@angular/common/http';
import { AdminReportTimeComponent } from './admin-report-time/admin-report-time.component';
import { ForgotPasswordComponent } from './forgot-password/forgot-password.component';
import { HomeComponent } from './home/home.component';
import { AdminAlltimeComponent } from './admin-alltime/admin-alltime.component';
import { NavbarAdminComponent } from './navbar-admin/navbar-admin.component';
import { AdminHistoryComponent } from './admin-history/admin-history.component';
import { AdminApproveComponent } from './admin-approve/admin-approve.component';
@Component({
  selector: 'app-root',
  standalone: true,
  imports: [
    RouterOutlet,
    NavbarAdminComponent,
    NavbarComponent,
    RegisterComponent,
    StadiumDetailComponent,
    LoginComponent,
    BookingTimeComponent,
    BookingMyComponent,
    QrCodeComponent,
    BookingConfirmComponent,
    BookingHistoryComponent,
    ProfileMainComponent,
    ProfileNameComponent,
    ProfilePasswordComponent,
    AdminMainComponent,
    AdminManageComponent,
    AdminReportComponent,
    AdminAddtypeComponent,
    AdminAddnumberComponent,
    AdminEdittypeComponent,
    AdminAllnumberComponent,
    AdminEditnumberComponent,
    AdminEdittimeComponent,
    HttpClientModule,
    AdminReportTimeComponent,
    ForgotPasswordComponent,
    HomeComponent,
    AdminAlltimeComponent,
    AdminHistoryComponent,
    AdminApproveComponent
  ],
  templateUrl: './app.component.html',
  styleUrl: './app.component.scss'
})
export class AppComponent {
  title = 'angular-web';
}
