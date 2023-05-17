import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { GlobalConstant } from '../component/common/global-constant';
import { TokenService } from './token.service';

@Injectable({
  providedIn: 'root'
})
export class CommonService {

  public baseUrl = GlobalConstant.apiURL;
  constructor(
    private http: HttpClient,
    private Token: TokenService
  ) { }

  datatable(data:any,view: any){
    return this.http.post(`${this.baseUrl}/`+ view +`-datatable`,data,{
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }

  list(view: any){
    return this.http.get(`${this.baseUrl}/`+ view +`-list`,{
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }

  save(data: any, view: any){
    return this.http.post(`${this.baseUrl}/`+ view +`/store`, data, {
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }

  show(id: any, view: any){
    return this.http.get(`${this.baseUrl}/`+ view +`/show/` + id, {
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }

  edit(id: any, view: any){
    return this.http.get(`${this.baseUrl}/`+ view +`/edit/` + id, {
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }

  update(data: any, view: any){
    return this.http.post(`${this.baseUrl}/`+ view +`/update`, data, {
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }

  active(data: any, view: any){
    return this.http.post(`${this.baseUrl}/`+ view +`/active/`, data, {
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }

  delete(id: any, view: any){
    return this.http.get(`${this.baseUrl}/`+ view +`/destroy/` + id, {
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }

  logout(){
    return this.http.get(`${this.baseUrl}/logout`, {
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }

  getUser(){
    return this.http.get(`${this.baseUrl}/get-user`, {
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }
  
  codeList(type: any, view: any){
    return this.http.get(`${this.baseUrl}/`+ view +`/code/` + type, {
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }
  
  getStateCountry(data: any){
    return this.http.get(`${this.baseUrl}/`+`get-state-country/` + data, {
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }
  
  getUsername(data: any){
    return this.http.get(`${this.baseUrl}/`+`user-name/` + data, {
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }
  
  login(data: any){
    return this.http.post(`${this.baseUrl}/login/`, data);
  }

  getByCode(code: any, view: any){
    return this.http.get(`${this.baseUrl}/`+ view +`/` + code, {
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }

  barcode_data(type: any, view: any){
    return this.http.get(`${this.baseUrl}/`+ view +`/barcode/` + type, {
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }

  branchState(view: any, state: any){
    return this.http.get(`${this.baseUrl}/`+ view +`/` + state, {
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }

  lotnoChange(view: any, id: any, barcode: any){
    return this.http.get(`${this.baseUrl}/`+ view +`/` + id +`/` + barcode, {
      headers: {Authorization: `Bearer ${this.Token.get()}` }
    });
  }
  
}
