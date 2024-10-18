// auth.service.ts
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  isAuthenticated(): boolean {
    return localStorage.getItem('isLoggedIn') === 'true';
  }
  isAdmin(): boolean {
    const userData = JSON.parse(localStorage.getItem('userData') || '{}');
    return userData.role === 'admin';
  }
  getUserData(): any {
    return JSON.parse(localStorage.getItem('userData') || '{}');
  }
}
