import { Routes } from '@angular/router';
import { RegisterComponent } from './register/register.component';
import { StadiumDetailComponent } from './stadium-detail/stadium-detail.component';
import { LoginComponent } from './login/login.component';
import { BookingTimeComponent } from './booking-time/booking-time.component';
import { BookingMyComponent } from './booking-my/booking-my.component';
import { QrCodeComponent } from './qr-code/qr-code.component';
import { BookingHistoryComponent } from './booking-history/booking-history.component';
import { BookingConfirmComponent } from './booking-confirm/booking-confirm.component';
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
import { AdminReportTimeComponent } from './admin-report-time/admin-report-time.component';
import { AdminReportMonthComponent } from './admin-report-month/admin-report-month.component';
import { ForgotPasswordComponent } from './forgot-password/forgot-password.component';
import { HomeComponent } from './home/home.component';
import { AdminAlltimeComponent } from './admin-alltime/admin-alltime.component';
import { AdminHistoryComponent } from './admin-history/admin-history.component';
import { AdminApproveComponent } from './admin-approve/admin-approve.component';
export const routes: Routes = [
    {'path': '', component: HomeComponent},
    {'path': 'home', component: HomeComponent},
    {'path': 'register', component: RegisterComponent},
    {'path': 'stadium-detail', component: StadiumDetailComponent},
    {'path': 'login', component: LoginComponent},
    {'path': 'booking-time', component: BookingTimeComponent},
    {'path': 'booking-my', component: BookingMyComponent},
    {'path': 'qr-code', component: QrCodeComponent},
    {'path': 'booking-confirm', component: BookingConfirmComponent},
    {'path': 'booking-history', component: BookingHistoryComponent},
    {'path': 'profile-main', component: ProfileMainComponent},
    {'path': 'profile-name', component: ProfileNameComponent},
    {'path': 'profile-password', component: ProfilePasswordComponent},
    {'path': 'admin-main', component: AdminMainComponent},
    {'path': 'admin-manage', component: AdminManageComponent},
    {'path': 'admin-report', component: AdminReportComponent},
    {'path': 'admin-addtype', component: AdminAddtypeComponent},
    {'path': 'admin-addnumber', component: AdminAddnumberComponent},
    {'path': 'admin-edittype', component: AdminEdittypeComponent},
    {'path': 'admin-allnumber', component: AdminAllnumberComponent},
    {'path': 'admin-editnumber', component: AdminEditnumberComponent},
    {'path': 'admin-edittime', component: AdminEdittimeComponent},
    {'path': 'admin-report-time', component: AdminReportTimeComponent},
    {'path': 'admin-report-month', component: AdminReportMonthComponent},
    {'path': 'forgot-password', component: ForgotPasswordComponent},
    {'path': 'admin-alltime', component: AdminAlltimeComponent},
    {'path': 'admin-history', component: AdminHistoryComponent},
    {'path': 'admin-approve', component: AdminApproveComponent},
];