import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})

export class DataServiceService {
  getStadiums() {
    throw new Error('Method not implemented.');
  }
  apiEndpoint = 'http://localhost/api_project';
  // apiEndpoint = '/api_project';
}

