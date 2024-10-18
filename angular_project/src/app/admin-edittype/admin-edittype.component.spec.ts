import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminEdittypeComponent } from './admin-edittype.component';

describe('AdminEdittypeComponent', () => {
  let component: AdminEdittypeComponent;
  let fixture: ComponentFixture<AdminEdittypeComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AdminEdittypeComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(AdminEdittypeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
