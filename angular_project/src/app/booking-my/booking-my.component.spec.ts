import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BookingMyComponent } from './booking-my.component';

describe('BookingMyComponent', () => {
  let component: BookingMyComponent;
  let fixture: ComponentFixture<BookingMyComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [BookingMyComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(BookingMyComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
