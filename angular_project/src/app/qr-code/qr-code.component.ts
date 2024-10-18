import { Component, OnInit, ViewChild, ElementRef, AfterViewInit, AfterViewChecked } from '@angular/core';
import { RouterLink, ActivatedRoute } from '@angular/router';
import { DataServiceService } from '../service/data.service.service';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';
import * as QRCode from 'qrcode';
import html2canvas from 'html2canvas';
import { saveAs } from 'file-saver';

@Component({
  selector: 'app-qr-code',
  standalone: true,
  imports: [RouterLink, CommonModule],
  providers: [DataServiceService],
  templateUrl: './qr-code.component.html',
  styleUrls: ['./qr-code.component.scss']
})
export class QrCodeComponent implements OnInit, AfterViewInit, AfterViewChecked {
  BookingUserid: any;
  @ViewChild('qrCodeImg') qrCodeImg!: ElementRef;
  @ViewChild('content') content!: ElementRef;

  constructor(
    private httpClient: HttpClient,
    private route: ActivatedRoute,
    private dataService: DataServiceService
  ) { }

  ngOnInit(): void {
    this.route.queryParams.subscribe((Params) => {
      const id_member = parseInt(Params['id_member']) || 0;
      if (id_member !== 0) {
        this.loadProfile(id_member);
      }
    });
  }

  ngAfterViewInit(): void {
    this.generateQRCode();
  }

  ngAfterViewChecked(): void {
    setTimeout(() => {
      this.generateQRCode();
    }, 0);
  }

  loadProfile(id_member: number): void {
    this.httpClient
      .get(`${this.dataService.apiEndpoint}/report_bookinguser/${id_member}`)
      .subscribe((data: any) => {
        this.BookingUserid = data;
        if (this.BookingUserid && this.BookingUserid.length > 0) {
          this.generateQRCode();
        }
      });
  }

  generateQRCode(): void {
    // Retrieve id_booking from BookingUserid if available
    const id_booking = this.BookingUserid && this.BookingUserid.length > 0 ? this.BookingUserid[0]?.id_booking : null;
    if (id_booking) {
      QRCode.toDataURL(`https://tinnawong.bowlab.net/admin-approve?id_booking=${id_booking}`, { width: 200 })
        .then((url: string) => {
          if (this.qrCodeImg && this.qrCodeImg.nativeElement) {
            this.qrCodeImg.nativeElement.src = url;
          }
        });
    }
  }

  formattedDate(date: Date): string {
    const options: Intl.DateTimeFormatOptions = { day: 'numeric', month: 'long', year: 'numeric' };
    return new Date(date).toLocaleDateString('th-TH', options);
  }

  downloadAsJpg(): void {
    if (this.content && this.content.nativeElement) {
      html2canvas(this.content.nativeElement).then(canvas => {
        canvas.toBlob(blob => {
          if (blob) {
            saveAs(blob, 'booking-qrcode.jpg');
            Swal.fire({
              icon: 'success',
              title: 'ดาวน์โหลดเสร็จสิ้น!',
              confirmButtonText: 'ตกลง', // เปลี่ยนเป็น 'ตกลง'
              confirmButtonColor: '#198b75',
            });
          }
        }, 'image/jpeg', 1);
      }).catch(error => {
        Swal.fire({
          icon: 'error',
          title: 'เกิดข้อผิดพลาด!',
          text: 'ไม่สามารถดาวน์โหลด QR Code ได้',
          confirmButtonText: 'ตกลง', // เปลี่ยนเป็น 'ตกลง'
          confirmButtonColor: '#198b75',
        });
      });
    }
  }
  
}
