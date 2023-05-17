import { Injectable } from '@angular/core';
import { TokenService } from './token.service';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class StorageService {

  public searchList: any = [];
  public resultList = new BehaviorSubject<any>([]);

  constructor() { }

  getList(){
    return this.resultList.asObservable();
  }

  addToList(data: any){
    this.searchList.push(data)
    this.resultList.next(this.searchList)
  }

  removeList(){
    this.searchList = []
    this.resultList.next(this.searchList);
  }

  
}
