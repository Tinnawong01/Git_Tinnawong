import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminAddnumberComponent } from './admin-addnumber.component';

describe('AdminAddnumberComponent', () => {
  let component: AdminAddnumberComponent;
  let fixture: ComponentFixture<AdminAddnumberComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AdminAddnumberComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(AdminAddnumberComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
