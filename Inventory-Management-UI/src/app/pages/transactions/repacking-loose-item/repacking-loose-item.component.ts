import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { AbstractControl, FormArray, FormBuilder, FormGroup, Validators } from '@angular/forms';

import jsPDF from 'jspdf';
import * as pdfMake from 'pdfmake/build/pdfmake.js';
import * as pdfFonts from 'pdfmake/build/vfs_fonts.js';
(pdfMake as any).vfs = pdfFonts.pdfMake.vfs;

import * as XLSX from 'xlsx';
import htmlToPdfmake from 'html-to-pdfmake';
import { Subject } from 'rxjs';
import { OpningStockService } from 'src/app/services/transactions/opning-stock.service';
import { DatePipe } from '@angular/common';

@Component({
  selector: 'app-repacking-loose-item',
  templateUrl: './repacking-loose-item.component.html',
  styleUrls: ['./repacking-loose-item.component.css']
})
export class RepackingLooseItemComponent {


  saleReturnForm!: FormGroup;

  constructor(private fb: FormBuilder, private OpeningStockHttp:OpningStockService,private datePipe: DatePipe) {
    this.createForm();
  }
  
  createForm() {
    this.saleReturnForm = this.fb.group({
      v_no: [''],
      v_date: [''],
      dept: [''],
      bulk_pcode: [''],
      created_by: [''],
      created_at: [''],
      updated_by: [''],
      updated_at: [''],
      locked_by: [''],
      locked_at: [''],
      list: this.fb.array([]), 
      tradlist: this.fb.array([]),
    });
  }

  ngOnInit(): void {
    for (let index = 0; index < 5; index++) {
      this.addList(); 
      this.addtradlist(); 
    }
  }

  get list() : FormArray {  
    return this.saleReturnForm.get("list") as FormArray  
  }  

  get tradlist() : FormArray {  
    return this.saleReturnForm.get("tradlist") as FormArray  
  }  
     
  newList(): FormGroup {  
    return this.fb.group({  
      lotno: '',
      doctype: '',
      qtytaken: '',
      qtycon: '',
      lossqty: '',
      gainqty: '',
      costrate: '',
      salerate: '',
      mrp: '',
      markup: ''
    })  
  }
     
  addList() {  
    this.list.push(this.newList());  
  }
     
  removeList(i:number) {  
    this.list.removeAt(i);  
  }
     
  newtradlist(): FormGroup {  
    return this.fb.group({  
      trade: '',
      codename: '',
      deptcode: '',
      packs: '',
      materialcost: '',
      pkcost: '',
      costrate: '',
      markup: '',
      mrp: '',
      salerate: ''
    })  
  }
     
  addtradlist() {  
    this.tradlist.push(this.newtradlist());  
  }
     
  removetradlist(i:number) {  
    this.tradlist.removeAt(i);  
  }

  onSubmit(){

  }

  downloadAsPDF(){

  }

  generateExcel(){

  }

  onReset(){}
  

} 
