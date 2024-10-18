import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminAddtypeComponent } from './admin-addtype.component';

describe('AdminAddtypeComponent', () => {
  let component: AdminAddtypeComponent;
  let fixture: ComponentFixture<AdminAddtypeComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AdminAddtypeComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(AdminAddtypeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
