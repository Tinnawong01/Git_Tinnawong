import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminAllnumberComponent } from './admin-allnumber.component';

describe('AdminAllnumberComponent', () => {
  let component: AdminAllnumberComponent;
  let fixture: ComponentFixture<AdminAllnumberComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AdminAllnumberComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(AdminAllnumberComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
