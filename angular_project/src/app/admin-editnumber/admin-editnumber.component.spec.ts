import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminEditnumberComponent } from './admin-editnumber.component';

describe('AdminEditnumberComponent', () => {
  let component: AdminEditnumberComponent;
  let fixture: ComponentFixture<AdminEditnumberComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AdminEditnumberComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(AdminEditnumberComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
