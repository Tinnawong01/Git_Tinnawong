import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminEdittimeComponent } from './admin-edittime.component';

describe('AdminEdittimeComponent', () => {
  let component: AdminEdittimeComponent;
  let fixture: ComponentFixture<AdminEdittimeComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AdminEdittimeComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(AdminEdittimeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
