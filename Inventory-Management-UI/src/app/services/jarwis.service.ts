import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { GlobalConstant } from '../component/common/global-constant';

@Injectable()
export class JarwisService {
  private baseUrl = GlobalConstant.apiURL;

  constructor(private http: HttpClient) { }

  register(data: any) {
    return this.http.post(`${this.baseUrl}/register`, data);
  }

  login(data: any) {
    return this.http.post(`${this.baseUrl}/login`, data);
  }

  socialAuthentication(data: any, type: any){
    return this.http.post(`${this.baseUrl}/social-auth-login`, data);
  }

  sendPasswordResetLink(data: any) {
    return this.http.post(`${this.baseUrl}/send-password-reset-link`, data);
  }

  get_userResetPassword(token: any){
    return this.http.get(`${this.baseUrl}/get-reset-email/`+ token);
  }
  
  changePassword(data: any) {
    return this.http.post(`${this.baseUrl}/reset-password`, data);
  }

  get__otp(data: any){
    return this.http.post(`${this.baseUrl}/generate-otp`, data);
  }

  set__otp(data: any){
    return this.http.post(`${this.baseUrl}/login-with-otp`, data);
  }

  get__registerotp(data: any){
    return this.http.post(`${this.baseUrl}/register-generate-otp`, data);
  }

  get__resendotp(data: any){
    return this.http.get(`${this.baseUrl}/resend-otp?phone=`+data);
  }

  set__registerotp(data: any){
    return this.http.post(`${this.baseUrl}/register-with-otp`, data);
  }

}