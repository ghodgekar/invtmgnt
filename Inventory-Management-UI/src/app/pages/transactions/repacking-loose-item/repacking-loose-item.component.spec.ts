import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RepackingLooseItemComponent } from './repacking-loose-item.component';

describe('RepackingLooseItemComponent', () => {
  let component: RepackingLooseItemComponent;
  let fixture: ComponentFixture<RepackingLooseItemComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RepackingLooseItemComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RepackingLooseItemComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
