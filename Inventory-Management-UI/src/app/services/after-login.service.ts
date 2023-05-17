import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, Router } from '@angular/router';
import { Observable } from 'rxjs/internal/Observable';
import { TokenService } from './token.service';

@Injectable({
  providedIn: 'root'
})
export class AfterLoginService {

  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean | Observable<boolean> | Promise<boolean> {
    if(this.Token.loggedIn()){
      return this.Token.loggedIn();
    }else{
      this.router.navigateByUrl('/login');
      return false;
    }
    
    
  }
  constructor(
    private Token: TokenService,
    private router: Router
    ) { }
    
}
