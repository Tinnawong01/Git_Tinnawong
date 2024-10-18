import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminReportMonthComponent } from './admin-report-month.component';

describe('AdminReportMonthComponent', () => {
  let component: AdminReportMonthComponent;
  let fixture: ComponentFixture<AdminReportMonthComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AdminReportMonthComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(AdminReportMonthComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
