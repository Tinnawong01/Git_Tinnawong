import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ProfileNameComponent } from './profile-name.component';

describe('ProfileNameComponent', () => {
  let component: ProfileNameComponent;
  let fixture: ComponentFixture<ProfileNameComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ProfileNameComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(ProfileNameComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
