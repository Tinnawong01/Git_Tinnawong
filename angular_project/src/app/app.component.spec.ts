import { TestBed } from '@angular/core/testing';
import { AppComponent } from './app.component'; // ปรับเปลี่ยนเป็นชื่อที่ถูกต้องของ AppComponent และตรวจสอบว่าตำแหน่งไฟล์ถูกต้อง

describe('AppComponent', () => {
  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [AppComponent], // ใช้ AppComponent ในส่วน declarations ไม่ใช่ imports
    }).compileComponents();
  });

  it('should create the app', () => {
    const fixture = TestBed.createComponent(AppComponent);
    const app = fixture.componentInstance;
    expect(app).toBeTruthy();
  });

  it(`should have the 'angular-web' title`, () => {
    const fixture = TestBed.createComponent(AppComponent);
    const app = fixture.componentInstance;
    expect(app.title).toEqual('angular-web');
  });

  it('should render title', () => {
    const fixture = TestBed.createComponent(AppComponent);
    fixture.detectChanges();
    const compiled = fixture.nativeElement as HTMLElement;
    expect(compiled.querySelector('h1')?.textContent).toContain('Hello, angular-web');
  });
});
