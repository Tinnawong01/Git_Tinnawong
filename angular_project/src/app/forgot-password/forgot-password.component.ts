import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { DataServiceService } from '../service/data.service.service';
import { ReactiveFormsModule } from '@angular/forms';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-forgot-password',
  standalone: true,
  imports: [RouterLink, ReactiveFormsModule],
  templateUrl: './forgot-password.component.html',
  styleUrls: ['./forgot-password.component.scss']
})
export class ForgotPasswordComponent implements OnInit {
  forgotPasswordForm!: FormGroup;

  constructor(
    private formBuilder: FormBuilder,
    private httpClient: HttpClient,
    private dataService: DataServiceService
  ) {}

  ngOnInit() {
    this.forgotPasswordForm = this.formBuilder.group({
      email: ['', [Validators.required, Validators.email]]
    });
  }

  onSubmit() {
    if (this.forgotPasswordForm.valid) {
      if (this.forgotPasswordForm.invalid) {
        Swal.fire({
          icon: 'warning',
          title: 'Invalid form',
          text: 'Please provide a valid email address.'
        });
        return;
      }
      const email = this.forgotPasswordForm.value.email;
      console.log('Email to send:', email); 
      console.log('Payload to send:', this.forgotPasswordForm.value);

      const headers = new HttpHeaders({ 'Content-Type': 'application/json' });
      this.httpClient.post<any>(`${this.dataService.apiEndpoint}/send-email`, { email: email }, { headers }).subscribe(
          (response) => {
              console.log('Email sent successfully!', response);
              Swal.fire({
                  icon: 'success',
                  title: 'Email sent successfully!',
                  text: 'Check your email for further instructions.'
              });
          },
          (error) => {
              console.error('Failed to send email:', error);
              Swal.fire({
                  icon: 'error',
                  title: 'Failed to send email',
                  text: 'Please try again later.'
              });
          }
      );
      
      
    }
  }
  
  
  
}