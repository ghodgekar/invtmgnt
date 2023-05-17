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
  selector: 'app-rate-adjustment',
  templateUrl: './rate-adjustment.component.html',
  styleUrls: ['./rate-adjustment.component.css']
})
export class RateAdjustmentComponent {

  rateForm!: FormGroup;
  submitted: boolean = false;
  data:any=[];
  parent_menu: any=[];
  submitBtn:String ='SAVE';

  @ViewChild('pdfTable')
  pdfTable!: ElementRef;

  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject();
  location!: any;

  constructor(private fb: FormBuilder, private OpeningStockHttp:OpningStockService,private datePipe: DatePipe) {
    this.createForm();
  }
  
  createForm() {
    this.rateForm = this.fb.group({
      loc_code: ['KMTH', Validators.required],
      bill_date: [this.datePipe.transform(new Date(), 'yyyy-MM-dd'), Validators.required ],
      item_code: ['', Validators.required ],
      qty: ['', Validators.required ],
      mrp: ['', Validators.required ],
      sale_rate: ['', Validators.required ],
      cost_rate: ['', Validators.required ],
      dept_code: ['', Validators.required ],
      expiry_date: ['', Validators.required ],
      batch_no: [''],
      doc_type: [''],
      comp_code: [''],
      item_name: [''],
      markup: [''],
      markdown: [''],
      created_by: ['Admin'],
      created_at: [''],
      _id: [],
      barcodes: this.fb.array([]), 
    });
  }

  ngOnInit(): void {
    this.location = localStorage.getItem('location')
  }

  get f(): { [key: string]: AbstractControl } {
    return this.rateForm.controls;
  }

  onSubmit(): void {
    this.rateForm.value['updated_by'] = localStorage.getItem('username');
    this.rateForm.value['updated_at'] = new Date();
    this.rateForm.value['status'] = 'Active';
    this.submitted = true;
    if (this.rateForm.invalid) {
      return;
    }else{
      if(this.submitBtn == 'SAVE'){
        this.rateForm.value['created_by'] = localStorage.getItem('username');
        this.rateForm.value['created_at'] = new Date();
      }else if(this.submitBtn == 'UPDATE'){
      }
    }
  }

  onReset(): void {
    this.submitted = false;
    this.rateForm.reset();
  }


  generateExcel() {  
  } 

  downloadAsPDF(): void
  {
  }
} 
