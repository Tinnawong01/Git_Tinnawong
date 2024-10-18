import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminAlltimeComponent } from './admin-alltime.component';

describe('AdminAlltimeComponent', () => {
  let component: AdminAlltimeComponent;
  let fixture: ComponentFixture<AdminAlltimeComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AdminAlltimeComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(AdminAlltimeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
