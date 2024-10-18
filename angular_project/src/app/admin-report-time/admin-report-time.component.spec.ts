import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminReportTimeComponent } from './admin-report-time.component';

describe('AdminReportTimeComponent', () => {
  let component: AdminReportTimeComponent;
  let fixture: ComponentFixture<AdminReportTimeComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AdminReportTimeComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(AdminReportTimeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
