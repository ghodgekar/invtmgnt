import { Injectable } from '@angular/core';
import { GlobalConstant } from '../component/common/global-constant';

@Injectable({
  providedIn: 'root'
})
export class TokenService {

  private iss = {
    login: GlobalConstant.apiURL+'/login',
    loginotp: GlobalConstant.apiURL+'/login-with-otp',
    authlogin: GlobalConstant.apiURL+'/social-auth-login',
    registerotp: GlobalConstant.apiURL+'/register-with-otp',
    signup: GlobalConstant.apiURL+'/register',
    resetpassword: GlobalConstant.apiURL+'/reset-password',
    guestcheckout: GlobalConstant.apiURL+'/guest-checkout'
  };
  constructor() { }

  handle(token: any) {
    this.set(token);
  }

  set(token: any){
    localStorage.setItem('token', token);
  }
  get() {
    return localStorage.getItem('token');
  }

  remove(){
    localStorage.removeItem('token');
  }

  isValid() {
    const token = this.get();
    if(token){
      const payload = this.payload(token);
      // console.log(payload);
      if(payload){
        return Object.values(this.iss).indexOf(payload.iss) > -1 ? true : false;
      }
    }
    return false;
  }

  payload(token: any){
    const payload = token.split('.')[1];
    return this.decode(payload);
  }

  decode(payload: any){
    return JSON.parse(atob(payload));
  }

  loggedIn(){
    return this.isValid();
  }
}
